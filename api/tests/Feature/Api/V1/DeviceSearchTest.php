<?php

declare(strict_types=1);

use App\Models\Device;
use App\Models\DeviceBrand;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function branded(string $name, string $slug, ?DeviceBrand $brand = null): Device
{
    return Device::query()->create([
        'name' => ['ru' => $name],
        'slug' => $slug,
        'brand_id' => $brand?->getKey(),
        'status' => true,
    ]);
}

function maker(string $name, string $slug): DeviceBrand
{
    return DeviceBrand::query()->create(['name' => $name, 'slug' => $slug, 'sort' => 1, 'status' => true]);
}

it('answers a device search instead of failing on the brand relation', function (): void {
    branded('ZLT X25', 'zlt-x25', maker('Tozed', 'tozed'));

    $this->getJson(route('api.v1.devices.index', ['search' => 'Tozed']))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.slug', 'zlt-x25');
});

it('finds a device by its own name too', function (): void {
    branded('ZLT X25', 'zlt-x25', maker('Tozed', 'tozed'));
    branded('Air Router', 'air-router', maker('Huawei', 'huawei'));

    $this->getJson(route('api.v1.devices.index', ['search' => 'Air']))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.slug', 'air-router');
});

it('leaves a device without a brand out of a brand search rather than erroring', function (): void {
    branded('No Brand', 'no-brand');

    $this->getJson(route('api.v1.devices.index', ['search' => 'Tozed']))
        ->assertOk()
        ->assertJsonCount(0, 'data');
});
