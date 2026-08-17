<?php

declare(strict_types=1);

use App\Enums\Network;
use App\Models\Service;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\TaxonomySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(TaxonomySeeder::class);
    $this->seed(ServiceSeeder::class);
});

it('lists every service in one page for the catalogue', function (): void {
    $this->getJson(route('api.v1.services.index', ['per_page' => 100]))
        ->assertOk()
        ->assertJsonCount(65, 'data');
});

it('splits the list by network', function (): void {
    $this->getJson(route('api.v1.services.index', ['network' => '5g', 'per_page' => 100]))
        ->assertOk()
        ->assertJsonCount(9, 'data');

    $this->getJson(route('api.v1.services.index', ['network' => 'cdma', 'per_page' => 100]))
        ->assertOk()
        ->assertJsonCount(56, 'data');
});

it('searches services by name', function (): void {
    $names = $this->getJson(route('api.v1.services.index', ['search' => 'АнтиАОН', 'per_page' => 100]))
        ->assertOk()
        ->json('data.*.name');

    expect($names)->not->toBeEmpty()
        ->and(collect($names)->every(fn (string $name): bool => str_contains($name, 'АнтиАОН')))->toBeTrue();
});

it('serves a service with its category and billing price', function (): void {
    $this->getJson(route('api.v1.services.show', ['service' => 'upravlyaemyi-antiaon-1']))
        ->assertOk()
        ->assertJsonPath('data.name', 'Управляемый АнтиАОН 1')
        ->assertJsonPath('data.price', '15 000 сум в месяц')
        ->assertJsonPath('data.network', 'cdma')
        ->assertJsonPath('data.category.name', 'Сетевые услуги')
        ->assertJsonPath('data.category.network', 'cdma');
});

it('resolves facts and steps down to the requested locale', function (): void {
    Service::query()->where('slug', 'upravlyaemyi-antiaon-1')->firstOrFail()->update([
        'ussd' => '*100*3#',
        'facts' => [['label' => ['ru' => 'Стоимость', 'uz' => 'Narxi'], 'value' => ['ru' => 'Бесплатно', 'uz' => 'Bepul']]],
        'steps' => [['text' => ['ru' => 'Наберите команду', 'uz' => 'Buyruqni tering'], 'code' => '*100*3#']],
    ]);

    $this->getJson(route('api.v1.services.show', ['service' => 'upravlyaemyi-antiaon-1']))
        ->assertOk()
        ->assertJsonPath('data.ussd', '*100*3#')
        ->assertJsonPath('data.facts.0.label', 'Стоимость')
        ->assertJsonPath('data.facts.0.value', 'Бесплатно')
        ->assertJsonPath('data.steps.0.text', 'Наберите команду')
        ->assertJsonPath('data.steps.0.code', '*100*3#');

    $this->getJson(route('api.v1.services.show', ['service' => 'upravlyaemyi-antiaon-1']), ['X-Locale' => 'uz'])
        ->assertOk()
        ->assertJsonPath('data.facts.0.label', 'Narxi')
        ->assertJsonPath('data.steps.0.text', 'Buyruqni tering');
});

it('filters the service categories by network', function (): void {
    $this->getJson(route('api.v1.categories', ['taxonomy' => 'service-categories', 'network' => '5g']))
        ->assertOk()
        ->assertJsonCount(2, 'data');
});

it('hides an unpublished service from the list and the page', function (): void {
    Service::query()->where('slug', 'upravlyaemyi-antiaon-1')->update(['status' => false]);

    $this->getJson(route('api.v1.services.index', ['per_page' => 100]))
        ->assertOk()
        ->assertJsonCount(64, 'data');

    expect(Service::query()->forNetwork(Network::Cdma)->published()->count())->toBe(55);
});
