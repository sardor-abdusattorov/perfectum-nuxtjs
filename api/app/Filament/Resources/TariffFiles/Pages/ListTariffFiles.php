<?php

namespace App\Filament\Resources\TariffFiles\Pages;

use App\Filament\Resources\TariffFiles\TariffFileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTariffFiles extends ListRecords
{
    protected static string $resource = TariffFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
