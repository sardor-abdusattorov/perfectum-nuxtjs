<?php

declare(strict_types=1);

use App\Enums\Network;
use App\Models\Tariff;
use App\Models\TariffCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('keeps a tariff inside the network of its category', function (): void {
    $cdma = TariffCategory::create(['name' => ['ru' => 'CDMA'], 'network' => Network::Cdma, 'status' => true]);
    $shared = TariffCategory::create(['name' => ['ru' => 'Общая'], 'network' => Network::Both, 'status' => true]);

    Tariff::create(['category_id' => $cdma->id, 'name' => ['ru' => 'Qulay 1'], 'slug' => 'qulay-1', 'status' => true]);
    Tariff::create(['category_id' => $shared->id, 'name' => ['ru' => 'Asl'], 'slug' => 'asl', 'status' => true]);

    expect(Tariff::published()->forNetwork(Network::Cdma)->pluck('slug')->all())
        ->toEqualCanonicalizing(['qulay-1', 'asl'])
        ->and(Tariff::published()->forNetwork(Network::FiveG)->pluck('slug')->all())
        ->toBe(['asl']);
});
