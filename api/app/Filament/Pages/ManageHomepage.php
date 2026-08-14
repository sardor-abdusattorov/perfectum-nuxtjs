<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Blocks\ManageBlocks;
use App\Filament\Pages\Homepage\AppPromoTab;
use App\Filament\Pages\Homepage\ChooseTab;
use App\Filament\Pages\Homepage\CoverageTab;
use App\Filament\Pages\Homepage\FeaturesTab;
use App\Filament\Pages\Homepage\HeroTab;
use App\Filament\Pages\Homepage\MarqueeTab;
use App\Filament\Pages\Homepage\TariffsTab;
use BackedEnum;

class ManageHomepage extends ManageBlocks
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $slug = 'homepage';

    public static function tabs(): array
    {
        return [
            HeroTab::class,
            MarqueeTab::class,
            ChooseTab::class,
            TariffsTab::class,
            FeaturesTab::class,
            CoverageTab::class,
            AppPromoTab::class,
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('app.label.homepage');
    }

    public static function getNavigationSort(): int
    {
        return 1;
    }

    public function getTitle(): string
    {
        return __('app.label.homepage');
    }
}
