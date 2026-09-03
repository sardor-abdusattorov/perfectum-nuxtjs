<?php

namespace App\Filament\Resources\ApplicationStatuses\Pages;

use App\Filament\Resources\ApplicationStatuses\ApplicationStatusResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewApplicationStatus extends ViewRecord
{
    protected static string $resource = ApplicationStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
