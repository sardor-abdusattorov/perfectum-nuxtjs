<?php

namespace App\Filament\Resources\ActionCategories\Pages;

use App\Filament\Resources\ActionCategories\ActionCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListActionCategories extends ListRecords
{
    protected static string $resource = ActionCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
