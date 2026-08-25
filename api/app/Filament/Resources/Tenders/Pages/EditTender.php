<?php

namespace App\Filament\Resources\Tenders\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\Tenders\TenderResource;
use App\Filament\Support\PreviewAction;
use App\Models\Tender;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTender extends EditRecord
{
    use GeneratesSlug;

    protected static string $resource = TenderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PreviewAction::make(fn (Tender $record): string => "/procurement/{$record->slug}"),
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
