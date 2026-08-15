<?php

namespace App\Filament\Resources\TariffTypes\Pages;

use App\Filament\Resources\TariffTypes\TariffTypeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTariffType extends ViewRecord
{
    protected static string $resource = TariffTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
