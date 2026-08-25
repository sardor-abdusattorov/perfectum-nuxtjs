<?php

namespace App\Filament\Resources\News\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\News\NewsResource;
use App\Filament\Support\PreviewAction;
use App\Models\News;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditNews extends EditRecord
{
    use GeneratesSlug;

    protected static string $resource = NewsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PreviewAction::make(fn (News $record): string => "/news/{$record->slug}"),
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
