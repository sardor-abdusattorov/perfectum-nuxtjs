<?php

namespace App\Filament\Resources\Tariffs\Pages;

use App\Enums\Network;
use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\Tariffs\TariffResource;
use App\Filament\Support\PreviewAction;
use App\Models\Tariff;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTariff extends EditRecord
{
    use GeneratesSlug;

    protected static string $resource = TariffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PreviewAction::make(fn (Tariff $record): string => $record->category?->network === Network::Cdma
                ? "/cdma/tariffs/{$record->slug}"
                : "/tariffs/{$record->slug}"),
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
