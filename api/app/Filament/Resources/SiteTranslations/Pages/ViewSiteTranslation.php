<?php

namespace App\Filament\Resources\SiteTranslations\Pages;

use App\Filament\Resources\SiteTranslations\SiteTranslationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSiteTranslation extends ViewRecord
{
    protected static string $resource = SiteTranslationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
