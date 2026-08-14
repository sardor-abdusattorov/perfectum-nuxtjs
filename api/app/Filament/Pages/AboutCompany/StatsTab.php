<?php

namespace App\Filament\Pages\AboutCompany;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Filament\Pages\Blocks\ContentTab;
use App\Filament\Pages\Blocks\SaveAction;
use App\Filament\Support\Fields;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs\Tab;

class StatsTab extends ContentTab
{
    public static function key(): ContentBlockKey
    {
        return ContentBlockKey::Stats;
    }

    public static function page(): PageKey
    {
        return PageKey::AboutCompany;
    }

    public static function make(): Tab
    {
        return Tab::make(__('app.section.stats'))
            ->schema([
                Repeater::make('stats.items')
                    ->label(__('app.label.stats'))
                    ->itemLabel(Fields::itemLabel('label'))
                    ->collapsible()
                    ->reorderable()
                    ->defaultItems(0)
                    ->schema([
                        TextInput::make('value')
                            ->label(__('app.label.value'))
                            ->required(),

                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('label')
                                    ->label(__('app.label.title'))
                                    ->required(),
                            ]),

                        Fields::status(),
                    ]),

                SaveAction::make(self::class),
            ]);
    }
}
