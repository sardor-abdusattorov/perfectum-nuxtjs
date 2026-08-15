<?php

namespace App\Filament\Resources\TariffFiles\Pages;

use App\Filament\Resources\TariffFiles\TariffFileResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTariffFile extends ViewRecord
{
    protected static string $resource = TariffFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
