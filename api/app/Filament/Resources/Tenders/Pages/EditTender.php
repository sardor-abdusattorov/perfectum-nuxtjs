<?php

namespace App\Filament\Resources\Tenders\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\Tenders\TenderResource;
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
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
