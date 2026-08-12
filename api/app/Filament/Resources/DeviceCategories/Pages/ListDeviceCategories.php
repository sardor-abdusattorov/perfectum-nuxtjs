<?php

namespace App\Filament\Resources\DeviceCategories\Pages;

use App\Filament\Resources\DeviceCategories\DeviceCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDeviceCategories extends ListRecords
{
    protected static string $resource = DeviceCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
