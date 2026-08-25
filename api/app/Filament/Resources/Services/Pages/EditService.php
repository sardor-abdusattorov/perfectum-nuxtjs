<?php

namespace App\Filament\Resources\Services\Pages;

use App\Enums\Network;
use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\Services\ServiceResource;
use App\Filament\Support\PreviewAction;
use App\Models\Service;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditService extends EditRecord
{
    use GeneratesSlug;

    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PreviewAction::make(fn (Service $record): string => $record->network === Network::Cdma
                ? "/cdma/services/{$record->slug}"
                : "/services/{$record->slug}"),
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
