<?php

namespace App\Filament\Resources\ServiceCategories\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\ServiceCategories\ServiceCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceCategory extends CreateRecord
{
    use GeneratesSlug;

    protected static string $resource = ServiceCategoryResource::class;
}
