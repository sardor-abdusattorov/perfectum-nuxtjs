<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Blocks\ManageBlocks;
use App\Filament\Pages\Cdma\CtaTab;
use App\Filament\Pages\Cdma\HeroTab;
use App\Filament\Pages\Cdma\SectionsTab;
use App\Filament\Pages\Cdma\SupportTab;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class ManageCdma extends ManageBlocks
{
    use HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-radio';

    protected static ?string $slug = 'cdma';

    public static function tabs(): array
    {
        return [HeroTab::class, SectionsTab::class, SupportTab::class, CtaTab::class];
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
