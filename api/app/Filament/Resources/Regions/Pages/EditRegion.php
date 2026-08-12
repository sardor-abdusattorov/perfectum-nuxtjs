<?php

namespace App\Filament\Resources\Regions\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\Regions\RegionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRegion extends EditRecord
{
    use GeneratesSlug;

    protected static string $resource = RegionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
