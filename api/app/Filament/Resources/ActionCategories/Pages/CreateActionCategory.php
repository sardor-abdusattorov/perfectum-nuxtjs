<?php

namespace App\Filament\Resources\ActionCategories\Pages;

use App\Filament\Resources\ActionCategories\ActionCategoryResource;
use App\Filament\Resources\Concerns\GeneratesSlug;
use Filament\Resources\Pages\CreateRecord;

class CreateActionCategory extends CreateRecord
{
    use GeneratesSlug;

    protected static string $resource = ActionCategoryResource::class;
}
