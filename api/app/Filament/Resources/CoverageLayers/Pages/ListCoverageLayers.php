<?php

namespace App\Filament\Resources\CoverageLayers\Pages;

use App\Filament\Resources\CoverageLayers\CoverageLayerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCoverageLayers extends ListRecords
{
    protected static string $resource = CoverageLayerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
