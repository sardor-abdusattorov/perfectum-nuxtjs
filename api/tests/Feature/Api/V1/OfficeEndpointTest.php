<?php

declare(strict_types=1);

use App\Enums\Network;
use App\Enums\OfficeType;
use App\Models\Office;
use App\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function region(string $slug): Region
{
    return Region::firstOrCreate(['slug' => $slug], [
        'name' => ['ru' => $slug, 'uz' => $slug],
        'sort' => 1,
        'status' => true,
    ]);
}

function office(array $attributes = []): Office
{
    return Office::create(array_merge([
        'type' => OfficeType::Office,
        'region_id' => region('toshkent')->id,
        'address' => ['ru' => 'улица Шевченко, 21', 'uz' => "Shevchenko ko'chasi, 21"],
        'district' => ['ru' => 'Мирабадский район', 'uz' => 'Mirobod tumani'],
        'lat' => 41.2975,
        'lng' => 69.2807,
        'sort' => 1,
        'status' => true,
    ], $attributes));
}

it('serves offices and dealers with their region', function (): void {
    office();

    $this->getJson(route('api.v1.offices'))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.type', 'office')
        ->assertJsonPath('data.0.region.slug', 'toshkent')
        ->assertJsonPath('data.0.address', 'улица Шевченко, 21')
        ->assertJsonPath('data.0.lat', 41.2975);
});

it('filters by type', function (): void {
    office();
    office(['type' => OfficeType::Dealer, 'name' => 'ABASA', 'sort' => 2]);

    $this->getJson(route('api.v1.offices', ['type' => 'dealer']))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'ABASA');
});

it('filters by region slug', function (): void {
    office();
    office(['region_id' => region('buxoro-viloyati')->id, 'sort' => 2]);

    $this->getJson(route('api.v1.offices', ['region' => 'buxoro-viloyati']))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.region.slug', 'buxoro-viloyati');
});

it('keeps a five g point out of the cdma section', function (): void {
    office(['network' => Network::FiveG]);

    $this->getJson(route('api.v1.offices', ['network' => 'cdma']))->assertOk()->assertJsonCount(0, 'data');
    $this->getJson(route('api.v1.offices', ['network' => '5g']))->assertOk()->assertJsonCount(1, 'data');
});

it('shows a shared point in both sections', function (): void {
    office();

    foreach (['5g', 'cdma'] as $network) {
        $this->getJson(route('api.v1.offices', ['network' => $network]))->assertOk()->assertJsonCount(1, 'data');
    }
});

it('hides an unpublished point', function (): void {
    office(['status' => false]);

    $this->getJson(route('api.v1.offices'))->assertOk()->assertJsonCount(0, 'data');
});

it('serves a point in the requested locale', function (): void {
    office();

    $this->getJson(route('api.v1.offices'), ['X-Locale' => 'uz'])
        ->assertOk()
        ->assertJsonPath('data.0.address', "Shevchenko ko'chasi, 21")
        ->assertJsonPath('data.0.district', 'Mirobod tumani');
});

it('lists the regions as a taxonomy', function (): void {
    region('toshkent');

    $this->getJson(route('api.v1.categories', ['taxonomy' => 'regions']))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.slug', 'toshkent');
});
