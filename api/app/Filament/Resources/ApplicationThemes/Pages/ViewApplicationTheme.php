<?php

namespace App\Filament\Resources\ApplicationThemes\Pages;

use App\Filament\Resources\ApplicationThemes\ApplicationThemeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewApplicationTheme extends ViewRecord
{
    protected static string $resource = ApplicationThemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
