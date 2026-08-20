<?php

declare(strict_types=1);

use App\Models\Device;
use App\Models\DeviceInstallment;
use App\Models\InstallmentPartner;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function router(): Device
{
    return Device::query()->create([
        'name' => ['ru' => 'Tozed ZLT X25 MAX2'],
        'slug' => 'tozed-zlt-x25-max2',
        'price' => 1850000,
        'in_stock' => true,
    ]);
}

function lender(string $slug = 'uzum-nasiya', string $name = 'Uzum nasiya', int $sort = 1): InstallmentPartner
{
    return InstallmentPartner::query()->create([
        'name' => $name,
        'slug' => $slug,
        'sort' => $sort,
        'status' => true,
    ]);
}

it('hands the device its instalment offers in the order the admin set', function (): void {
    $device = router();

    DeviceInstallment::query()->create([
        'device_id' => $device->getKey(),
        'installment_partner_id' => lender('alif', 'Alif', 2)->getKey(),
        'sort' => 2,
        'options' => [['term' => 1, 'monthly' => 1979500, 'total' => 1979500]],
    ]);

    DeviceInstallment::query()->create([
        'device_id' => $device->getKey(),
        'installment_partner_id' => lender()->getKey(),
        'sort' => 1,
        'options' => [
            ['term' => 12, 'monthly' => 222000, 'total' => 2664000],
            ['term' => 6, 'monthly' => 397750, 'total' => 2386500],
        ],
    ]);

    $this->getJson(route('api.v1.devices.show', ['device' => 'tozed-zlt-x25-max2']))
        ->assertOk()
        ->assertJsonPath('data.installments.0.partner.name', 'Uzum nasiya')
        ->assertJsonPath('data.installments.0.options.0.term', 6)
        ->assertJsonPath('data.installments.0.options.0.monthly', 397750)
        ->assertJsonPath('data.installments.0.options.1.term', 12)
        ->assertJsonPath('data.installments.1.partner.name', 'Alif');
});

it('completes the figure the partner did not quote', function (): void {
    $device = router();

    DeviceInstallment::query()->create([
        'device_id' => $device->getKey(),
        'installment_partner_id' => lender()->getKey(),
        'options' => [
            ['term' => 6, 'monthly' => 300000, 'total' => null],
            ['term' => 12, 'monthly' => null, 'total' => 2400000],
        ],
    ]);

    $this->getJson(route('api.v1.devices.show', ['device' => 'tozed-zlt-x25-max2']))
        ->assertOk()
        ->assertJsonPath('data.installments.0.options.0.total', 1800000)
        ->assertJsonPath('data.installments.0.options.1.monthly', 200000);
});

it('keeps an empty offer out of the payload', function (): void {
    $device = router();

    DeviceInstallment::query()->create([
        'device_id' => $device->getKey(),
        'installment_partner_id' => lender()->getKey(),
        'options' => [['term' => 6, 'monthly' => 0, 'total' => 0]],
    ]);

    $this->getJson(route('api.v1.devices.show', ['device' => 'tozed-zlt-x25-max2']))
        ->assertOk()
        ->assertJsonPath('data.installments', []);
});

it('leaves the instalments out of the catalogue listing', function (): void {
    $device = router();

    DeviceInstallment::query()->create([
        'device_id' => $device->getKey(),
        'installment_partner_id' => lender()->getKey(),
        'options' => [['term' => 6, 'monthly' => 397750, 'total' => 2386500]],
    ]);

    $this->getJson(route('api.v1.devices.index'))
        ->assertOk()
        ->assertJsonPath('data.0.installments', []);
});

it('seeds the offers the old site quoted for the router', function (): void {
    $this->seed(Database\Seeders\DeviceSeeder::class);
    $this->seed(Database\Seeders\InstallmentSeeder::class);

    $this->getJson(route('api.v1.devices.show', ['device' => 'tozed-zlt-x25-max2']))
        ->assertOk()
        ->assertJsonPath('data.installments.0.partner.slug', 'uzum-nasiya')
        ->assertJsonPath('data.installments.0.options.0.monthly', 397750)
        ->assertJsonPath('data.installments.1.partner.slug', 'alif')
        ->assertJsonPath('data.installments.1.options.0.total', 1979500);
});
