<?php

namespace App\Filament\Resources\FreeNumberFilters\Pages;

use App\Filament\Resources\FreeNumberFilters\FreeNumberFilterResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFreeNumberFilter extends ViewRecord
{
    protected static string $resource = FreeNumberFilterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
