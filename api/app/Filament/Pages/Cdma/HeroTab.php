<?php

namespace App\Filament\Pages\Cdma;

use App\Enums\PageKey;
use App\Filament\Pages\Blocks\PageHeroTab;

class HeroTab extends PageHeroTab
{
    public static function page(): PageKey
    {
        return PageKey::Cdma;
    }
}
