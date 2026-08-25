<?php

namespace App\Filament\Resources\DeviceCategories\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\DeviceCategories\DeviceCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDeviceCategory extends CreateRecord
{
    use GeneratesSlug;

    protected static string $resource = DeviceCategoryResource::class;
}
