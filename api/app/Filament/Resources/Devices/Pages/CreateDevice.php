<?php

namespace App\Filament\Resources\Devices\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\Devices\DeviceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDevice extends CreateRecord
{
    use GeneratesSlug;

    protected static string $resource = DeviceResource::class;
}
