<?php

namespace App\Filament\Resources\TariffCategories\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\TariffCategories\TariffCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTariffCategory extends CreateRecord
{
    use GeneratesSlug;

    protected static string $resource = TariffCategoryResource::class;
}
