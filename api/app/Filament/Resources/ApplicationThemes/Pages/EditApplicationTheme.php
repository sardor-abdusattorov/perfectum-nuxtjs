<?php

namespace App\Filament\Resources\ApplicationThemes\Pages;

use App\Filament\Resources\ApplicationThemes\ApplicationThemeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditApplicationTheme extends EditRecord
{
    protected static string $resource = ApplicationThemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
