<?php

declare(strict_types=1);

use App\Models\Device;
use App\Models\DeviceBrand;
use Database\Seeders\DeviceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Storage::fake('public');
    $this->seed(DeviceSeeder::class);
});

it('lists the seeded devices with their categories', function (): void {
    $this->getJson(route('api.v1.devices.index'))
        ->assertOk()
        ->assertJsonPath('meta.total', Device::query()->published()->count())
        ->assertJsonPath('data.0.slug', 'amgoo-cx8r')
        ->assertJsonPath('data.0.brand.name', 'AMGOO')
        ->assertJsonPath('data.0.brand.slug', 'amgoo')
        ->assertJsonPath('data.0.category.network', 'cdma');
});

it('filters devices by network', function (): void {
    $networks = collect($this->getJson(route('api.v1.devices.index', ['network' => '5g']))
        ->assertOk()
        ->json('data'))
        ->pluck('category.network');

    expect($networks)->not->toBeEmpty()->each->toBe('5g');
});

it('serves a device with specs resolved to the locale', function (): void {
    $this->getJson(route('api.v1.devices.show', ['device' => 'tozed-zlt-x25-max2']))
        ->assertOk()
        ->assertJsonPath('data.name', 'Tozed ZLT X25 MAX2')
        ->assertJsonPath('data.price', 1850000)
        ->assertJsonPath('data.in_stock', true)
        ->assertJsonPath('data.specs.0.label', 'Тип устройства:')
        ->assertJsonPath('data.specs.0.value', 'Домашний 5G роутер (Indoor CPE)');

    $this->getJson(route('api.v1.devices.show', ['device' => 'tozed-zlt-x25-max2']), ['X-Locale' => 'uz'])
        ->assertOk()
        ->assertJsonPath('data.specs.0.label', 'Qurilma turi:')
        ->assertJsonPath('data.specs.0.value', 'Uy 5G routeri (Indoor CPE)');
});

it('ships the router photo with the seeder', function (): void {
    $image = $this->getJson(route('api.v1.devices.show', ['device' => 'tozed-zlt-x25-max2']))
        ->assertOk()
        ->json('data.image');

    expect($image)->toContain('uploads/devices/tozed-zlt-x25-max2.jpg');
});

it('lists the device categories with their networks', function (): void {
    $this->getJson(route('api.v1.categories', ['taxonomy' => 'device-categories']))
        ->assertOk()
        ->assertJsonCount(3, 'data')
        ->assertJsonPath('data.0.name', 'Устройства CDMA')
        ->assertJsonPath('data.0.network', 'cdma')
        ->assertJsonPath('data.2.name', 'Роутеры')
        ->assertJsonPath('data.2.network', '5g');
});

it('hands the brand over as a record the admin owns', function (): void {
    $brand = DeviceBrand::query()->where('slug', 'amgoo')->firstOrFail();
    $brand->update(['color' => '#b56a3a']);

    $this->getJson(route('api.v1.devices.show', ['device' => 'amgoo-cx8r']))
        ->assertOk()
        ->assertJsonPath('data.brand.name', 'AMGOO')
        ->assertJsonPath('data.brand.color', '#b56a3a')
        ->assertJsonPath('data.brand.logo', null);
});
