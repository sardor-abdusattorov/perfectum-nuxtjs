<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Blocks\ManageBlocks;
use App\Filament\Pages\CdmaConnect\ArticleTab;
use App\Filament\Pages\CdmaConnect\HeroTab;
use BackedEnum;

class ManageCdmaConnect extends ManageBlocks
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-signal';

    protected static ?string $slug = 'cdma-connect';

    public static function tabs(): array
    {
        return [HeroTab::class, ArticleTab::class];
    }

    public static function getNavigationLabel(): string
    {
        return __('app.page.cdma_connect');
    }

    public static function getNavigationSort(): int
    {
        return 5;
    }

    public function getTitle(): string
    {
        return __('app.page.cdma_connect');
    }
}
