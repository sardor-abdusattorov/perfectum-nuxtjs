<?php

declare(strict_types=1);

use App\Models\Service;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\TaxonomySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(TaxonomySeeder::class);
    $this->seed(ServiceSeeder::class);
});

it('carries every service of the old site into its category', function (): void {
    expect(Service::count())->toBe(65)
        ->and(Service::query()->whereNull('category_id')->count())->toBe(0)
        ->and(Service::query()->published()->count())->toBe(65);
});

it('keeps both languages and the billing price of a service', function (): void {
    $service = Service::query()->where('slug', 'upravlyaemyi-antiaon-1')->firstOrFail();

    expect($service->getTranslation('name', 'ru'))->toBe('Управляемый АнтиАОН 1')
        ->and($service->getTranslation('name', 'uz'))->toBe('Boshqariladigan Anti ARA 1')
        ->and($service->getTranslation('price', 'ru'))->toBe('15 000 сум в месяц')
        ->and($service->category->getTranslation('name', 'ru'))->toBe('Сетевые услуги');
});

it('hands the editor content with the old markup junk stripped', function (): void {
    $stale = Service::query()
        ->get()
        ->filter(fn (Service $service): bool => str_contains((string) $service->getTranslation('content', 'ru'), 'style='))
        ->count();

    expect($stale)->toBe(0);
});

it('survives a rerun without doubling the rows', function (): void {
    $this->seed(ServiceSeeder::class);

    expect(Service::count())->toBe(65);
});
