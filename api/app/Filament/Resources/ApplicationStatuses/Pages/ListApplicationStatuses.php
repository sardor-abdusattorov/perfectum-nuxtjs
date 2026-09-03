<?php

namespace App\Filament\Resources\ApplicationStatuses\Pages;

use App\Filament\Resources\ApplicationStatuses\ApplicationStatusResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApplicationStatuses extends ListRecords
{
    protected static string $resource = ApplicationStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
