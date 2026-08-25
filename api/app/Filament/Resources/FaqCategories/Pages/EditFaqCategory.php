<?php

namespace App\Filament\Resources\FaqCategories\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\FaqCategories\FaqCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFaqCategory extends EditRecord
{
    use GeneratesSlug;

    protected static string $resource = FaqCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
