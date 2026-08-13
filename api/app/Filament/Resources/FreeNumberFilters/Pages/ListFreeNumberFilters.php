<?php

namespace App\Filament\Resources\FreeNumberFilters\Pages;

use App\Filament\Resources\FreeNumberFilters\FreeNumberFilterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFreeNumberFilters extends ListRecords
{
    protected static string $resource = FreeNumberFilterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
