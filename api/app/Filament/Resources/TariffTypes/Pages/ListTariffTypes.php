<?php

namespace App\Filament\Resources\TariffTypes\Pages;

use App\Filament\Resources\TariffTypes\TariffTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTariffTypes extends ListRecords
{
    protected static string $resource = TariffTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
