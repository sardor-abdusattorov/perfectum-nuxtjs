<?php

namespace App\Filament\Resources\FaqCategories\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\FaqCategories\FaqCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFaqCategory extends CreateRecord
{
    use GeneratesSlug;

    protected static string $resource = FaqCategoryResource::class;
}
