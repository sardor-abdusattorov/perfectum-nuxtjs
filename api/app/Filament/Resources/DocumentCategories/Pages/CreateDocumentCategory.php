<?php

namespace App\Filament\Resources\DocumentCategories\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\DocumentCategories\DocumentCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDocumentCategory extends CreateRecord
{
    use GeneratesSlug;

    protected static string $resource = DocumentCategoryResource::class;
}
