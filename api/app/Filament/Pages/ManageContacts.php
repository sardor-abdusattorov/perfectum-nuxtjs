<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Blocks\ManageBlocks;
use App\Filament\Pages\Contacts\CardsTab;
use App\Filament\Pages\Contacts\HeroTab;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class ManageContacts extends ManageBlocks
{
    use HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-phone';

    protected static ?string $slug = 'contacts-page';

    public static function tabs(): array
    {
        return [HeroTab::class, CardsTab::class];
    }

    public static function getNavigationLabel(): string
    {
        return __('app.page.contacts');
    }

    public static function getNavigationSort(): int
    {
        return 3;
    }

    public function getTitle(): string
    {
        return __('app.page.contacts');
    }
}
