<?php

namespace App\Filament\Resources\Tariffs\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\Tariffs\TariffResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTariff extends CreateRecord
{
    use GeneratesSlug;

    protected static string $resource = TariffResource::class;

    protected function slugSource(): string
    {
        return 'name';
    }
}
