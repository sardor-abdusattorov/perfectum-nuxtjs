<?php

namespace App\Filament\Pages\Homepage;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\ContentBlockKey;
use App\Filament\Support\TabSaveAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs\Tab;

class MarqueeTab extends ContentTab
{
    public static function key(): ContentBlockKey
    {
        return ContentBlockKey::Marquee;
    }

    public static function make(): Tab
    {
        return Tab::make(__('app.section.marquee'))
            ->schema([
                TranslatableTabs::make('translations')
                    ->schema([
                        Repeater::make('marquee.items')
                            ->label(__('app.label.marquee_items'))
                            ->helperText(__('app.helper.marquee_items'))
                            ->simple(
                                TextInput::make('text')->required(),
                            )
                            ->defaultItems(0)
                            ->reorderable(),
                    ]),

                TabSaveAction::make('marquee', self::class),
            ]);
    }
}
