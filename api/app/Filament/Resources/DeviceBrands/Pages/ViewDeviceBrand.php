<?php

namespace App\Filament\Resources\DeviceBrands\Pages;

use App\Filament\Resources\DeviceBrands\DeviceBrandResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDeviceBrand extends ViewRecord
{
    protected static string $resource = DeviceBrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
