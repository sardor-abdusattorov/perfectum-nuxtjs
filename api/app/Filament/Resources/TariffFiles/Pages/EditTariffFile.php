<?php

namespace App\Filament\Resources\TariffFiles\Pages;

use App\Filament\Resources\TariffFiles\TariffFileResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTariffFile extends EditRecord
{
    protected static string $resource = TariffFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
