<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Blocks\ManageBlocks;
use App\Filament\Pages\Documents\HeroTab;
use BackedEnum;

class ManageDocuments extends ManageBlocks
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $slug = 'documents-page';

    public static function tabs(): array
    {
        return [HeroTab::class];
    }

    public static function getNavigationLabel(): string
    {
        return __('app.page.documents');
    }

    public static function getNavigationSort(): int
    {
        return 5;
    }

    public function getTitle(): string
    {
        return __('app.page.documents');
    }
}
