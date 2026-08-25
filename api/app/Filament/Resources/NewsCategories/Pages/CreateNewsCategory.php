<?php

namespace App\Filament\Resources\NewsCategories\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\NewsCategories\NewsCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNewsCategory extends CreateRecord
{
    use GeneratesSlug;

    protected static string $resource = NewsCategoryResource::class;
}
