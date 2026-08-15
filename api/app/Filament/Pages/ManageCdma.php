<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Blocks\ManageBlocks;
use App\Filament\Pages\Cdma\CtaTab;
use App\Filament\Pages\Cdma\HeroTab;
use App\Filament\Pages\Cdma\SupportTab;
use BackedEnum;

class ManageCdma extends ManageBlocks
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-radio';

    protected static ?string $slug = 'cdma';

    public static function tabs(): array
    {
        return [HeroTab::class, SupportTab::class, CtaTab::class];
    }

    public static function getNavigationLabel(): string
    {
        return __('app.page.cdma');
    }

    public static function getNavigationSort(): int
    {
        return 4;
    }

    public function getTitle(): string
    {
        return __('app.page.cdma');
    }
}
