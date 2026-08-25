<?php

namespace App\Filament\Resources\TariffTypes\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\TariffTypes\TariffTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTariffType extends CreateRecord
{
    use GeneratesSlug;

    protected static string $resource = TariffTypeResource::class;
}
