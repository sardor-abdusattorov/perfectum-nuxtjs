<?php

namespace App\Filament\Resources\TariffCategories\Pages;

use App\Filament\Resources\TariffCategories\TariffCategoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTariffCategory extends ViewRecord
{
    protected static string $resource = TariffCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
