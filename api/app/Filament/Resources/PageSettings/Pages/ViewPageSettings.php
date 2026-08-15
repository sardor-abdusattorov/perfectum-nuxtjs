<?php

namespace App\Filament\Resources\PageSettings\Pages;

use App\Filament\Resources\PageSettings\PageSettingsResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPageSettings extends ViewRecord
{
    protected static string $resource = PageSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
