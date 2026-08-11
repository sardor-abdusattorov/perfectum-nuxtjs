<?php

namespace App\Filament\Pages\Homepage;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\ContentBlockKey;
use App\Filament\Support\MultilineText;
use App\Filament\Support\TabSaveAction;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;

class AppPromoTab extends ContentTab
{
    public static function key(): ContentBlockKey
    {
        return ContentBlockKey::AppPromo;
    }

    public static function make(): Tab
    {
        return Tab::make(__('app.section.app_promo'))
            ->schema([
                Section::make(__('app.label.section_texts'))
                    ->schema([
                        TranslatableTabs::make('translations')
                            ->schema([
                                MultilineText::make('app_promo.title')
                                    ->label(__('app.label.title')),

                                MultilineText::make('app_promo.description')
                                    ->label(__('app.label.description')),
                            ]),

                        TextInput::make('app_promo.watermark')
                            ->label(__('app.label.watermark'))
                            ->helperText(__('app.helper.watermark')),
                    ]),

                Section::make(__('app.label.app_links'))
                    ->schema([
                        TextInput::make('app_promo.app_store_url')
                            ->label(__('app.label.app_store_url')),

                        TextInput::make('app_promo.google_play_url')
                            ->label(__('app.label.google_play_url')),
                    ])
                    ->columns(2),

                TabSaveAction::make('app_promo', self::class),
            ]);
    }
}
