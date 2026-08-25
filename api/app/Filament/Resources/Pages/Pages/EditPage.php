<?php

namespace App\Filament\Resources\Pages\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\Pages\PageResource;
use App\Filament\Support\PreviewAction;
use App\Models\Page;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    use GeneratesSlug;

    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PreviewAction::make(fn (Page $record): string => "/pages/{$record->slug}"),
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
