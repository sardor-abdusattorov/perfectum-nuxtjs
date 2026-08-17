<?php

declare(strict_types=1);

use Database\Seeders\DeviceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(DeviceSeeder::class);
});

it('lists the seeded devices with their categories', function (): void {
    $this->getJson(route('api.v1.devices.index'))
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.slug', 'tozed-zlt-x25-max2')
        ->assertJsonPath('data.0.category.network', '5g')
        ->assertJsonPath('data.1.slug', 'amgoo-cx8r')
        ->assertJsonPath('data.1.brand', 'AMGOO')
        ->assertJsonPath('data.1.category.network', 'cdma');
});

it('filters devices by network', function (): void {
    $this->getJson(route('api.v1.devices.index', ['network' => 'cdma']))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.slug', 'amgoo-cx8r');
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
