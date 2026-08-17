<?php

namespace App\Filament\Resources\DeviceBrands\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\DeviceBrands\DeviceBrandResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDeviceBrand extends CreateRecord
{
    use GeneratesSlug;

    protected static string $resource = DeviceBrandResource::class;
}
