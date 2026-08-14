<?php

namespace App\Filament\Resources\ApplicationThemes\Pages;

use App\Filament\Resources\ApplicationThemes\ApplicationThemeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApplicationThemes extends ListRecords
{
    protected static string $resource = ApplicationThemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
