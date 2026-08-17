<?php

namespace App\Filament\Pages;

use App\Filament\Pages\AboutCompany\HeroTab;
use App\Filament\Pages\AboutCompany\IntroTab;
use App\Filament\Pages\AboutCompany\StatsTab;
use App\Filament\Pages\AboutCompany\TimelineTab;
use App\Filament\Pages\Blocks\ManageBlocks;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class ManageAboutCompany extends ManageBlocks
{
    use HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $slug = 'about-company';

    public static function tabs(): array
    {
        return [HeroTab::class, StatsTab::class, IntroTab::class, TimelineTab::class];
    }

    public static function getNavigationLabel(): string
    {
        return __('app.page.about_company');
    }

    public static function getNavigationSort(): int
    {
        return 2;
    }

    public function getTitle(): string
    {
        return __('app.page.about_company');
    }
}
