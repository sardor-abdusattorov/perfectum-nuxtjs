<?php

namespace App\Filament\Resources\ActionCategories\Pages;

use App\Filament\Resources\ActionCategories\ActionCategoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewActionCategory extends ViewRecord
{
    protected static string $resource = ActionCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
