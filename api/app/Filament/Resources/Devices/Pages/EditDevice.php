<?php

namespace App\Filament\Resources\Devices\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\Devices\DeviceResource;
use App\Filament\Support\PreviewAction;
use App\Models\Device;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDevice extends EditRecord
{
    use GeneratesSlug;

    protected static string $resource = DeviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PreviewAction::make(fn (Device $record): string => "/devices/{$record->slug}"),
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
