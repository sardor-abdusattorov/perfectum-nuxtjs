<?php

namespace App\Filament\Resources\PageSettings\Pages;

use App\Filament\Resources\PageSettings\PageSettingsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPageSettings extends EditRecord
{
    protected static string $resource = PageSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
