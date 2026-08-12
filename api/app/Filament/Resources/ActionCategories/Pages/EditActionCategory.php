<?php

namespace App\Filament\Resources\ActionCategories\Pages;

use App\Filament\Resources\ActionCategories\ActionCategoryResource;
use App\Filament\Resources\Concerns\GeneratesSlug;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditActionCategory extends EditRecord
{
    use GeneratesSlug;

    protected static string $resource = ActionCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
