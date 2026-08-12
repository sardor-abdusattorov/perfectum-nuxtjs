<?php

declare(strict_types=1);

use App\Enums\Network;
use App\Models\Tariff;
use App\Models\TariffCategory;
use App\Models\TariffType;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lets the tariff type pin a tariff to a network', function (): void {
    $shared = TariffCategory::create(['name' => ['ru' => 'Общая'], 'slug' => 'obshaya', 'network' => Network::Both, 'status' => true]);
    $cdmaOnly = TariffType::create(['name' => ['ru' => 'Qulay'], 'slug' => 'qulay', 'network' => Network::Cdma, 'status' => true]);
    $anywhere = TariffType::create(['name' => ['ru' => 'Любой'], 'slug' => 'lyuboi', 'network' => Network::Both, 'status' => true]);

    Tariff::create(['category_id' => $shared->id, 'type_id' => $cdmaOnly->id, 'name' => ['ru' => 'Qulay 1'], 'slug' => 'qulay-1', 'status' => true]);
    Tariff::create(['category_id' => $shared->id, 'type_id' => $anywhere->id, 'name' => ['ru' => 'Asl'], 'slug' => 'asl', 'status' => true]);

    expect(Tariff::published()->forNetwork(Network::Cdma)->pluck('slug')->all())
        ->toEqualCanonicalizing(['qulay-1', 'asl'])
        ->and(Tariff::published()->forNetwork(Network::FiveG)->pluck('slug')->all())
        ->toBe(['asl']);
});
