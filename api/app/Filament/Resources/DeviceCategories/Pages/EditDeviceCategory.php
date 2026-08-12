<?php

namespace App\Filament\Resources\DeviceCategories\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\DeviceCategories\DeviceCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDeviceCategory extends EditRecord
{
    use GeneratesSlug;

    protected static string $resource = DeviceCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
