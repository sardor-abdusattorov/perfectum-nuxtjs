<?php

namespace App\Filament\Resources\Actions\Pages;

use App\Enums\Network;
use App\Filament\Resources\Actions\ActionResource;
use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Support\PreviewAction;
use App\Models\Action as ActionRecord;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAction extends EditRecord
{
    use GeneratesSlug;

    protected static string $resource = ActionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PreviewAction::make(fn (ActionRecord $record): string => $record->network === Network::Cdma
                ? "/cdma/actions/{$record->slug}"
                : "/actions/{$record->slug}"),
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
