<?php

namespace App\Filament\Resources\CoverageLayers\Pages;

use App\Filament\Resources\CoverageLayers\CoverageLayerResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCoverageLayer extends ViewRecord
{
    protected static string $resource = CoverageLayerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
