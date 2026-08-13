<?php

namespace App\Filament\Resources\FreeNumberFilters\Pages;

use App\Filament\Resources\FreeNumberFilters\FreeNumberFilterResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFreeNumberFilter extends EditRecord
{
    protected static string $resource = FreeNumberFilterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
