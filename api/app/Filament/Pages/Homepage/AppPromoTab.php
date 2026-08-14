<?php

namespace App\Filament\Pages\Homepage;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Filament\Pages\Blocks\ContentTab;
use App\Filament\Pages\Blocks\SaveAction;
use App\Filament\Support\Fields;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;

class AppPromoTab extends ContentTab
{
    public static function key(): ContentBlockKey
    {
        return ContentBlockKey::AppPromo;
    }

    public static function page(): PageKey
    {
        return PageKey::Home;
    }

    public static function make(): Tab
    {
        return Tab::make(__('app.section.app_promo'))
            ->schema([
                Section::make(__('app.label.section_texts'))
                    ->schema([
                        TranslatableTabs::make('translations')
                            ->schema([
                                Fields::multiline('app_promo.title')
                                    ->label(__('app.label.title'))
                                    ->required(),

                                Fields::multiline('app_promo.description')
                                    ->label(__('app.label.description')),
                            ]),

                        TextInput::make('app_promo.watermark')
                            ->label(__('app.label.watermark'))
                            ->helperText(__('app.helper.watermark')),
                    ]),

                SaveAction::make(self::class),
            ]);
    }
}
