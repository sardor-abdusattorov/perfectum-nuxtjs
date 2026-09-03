<?php

namespace App\Filament\Resources\ApplicationStatuses\Pages;

use App\Filament\Resources\ApplicationStatuses\ApplicationStatusResource;
use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Models\ApplicationStatus;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditApplicationStatus extends EditRecord
{
    use GeneratesSlug;

    protected static string $resource = ApplicationStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->authorize(fn (ApplicationStatus $record): bool => ! $record->isInUse())
                ->authorizationTooltip()
                ->authorizationMessage(__('app.helper.status_in_use')),
        ];
    }
}
