<?php

namespace App\Filament\Resources\DeviceCategories\Pages;

use App\Filament\Resources\DeviceCategories\DeviceCategoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDeviceCategory extends ViewRecord
{
    protected static string $resource = DeviceCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
