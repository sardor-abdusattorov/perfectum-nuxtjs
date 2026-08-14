<?php

namespace App\Filament\Pages\AboutCompany;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Filament\Pages\Blocks\ContentTab;
use App\Filament\Pages\Blocks\SaveAction;
use App\Filament\Support\Fields;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs\Tab;

class TimelineTab extends ContentTab
{
    public static function key(): ContentBlockKey
    {
        return ContentBlockKey::Timeline;
    }

    public static function page(): PageKey
    {
        return PageKey::AboutCompany;
    }

    public static function make(): Tab
    {
        return Tab::make(__('app.section.timeline'))
            ->schema([
                Repeater::make('timeline.items')
                    ->label(__('app.label.timeline'))
                    ->itemLabel(Fields::itemLabel('year'))
                    ->collapsible()
                    ->reorderable()
                    ->defaultItems(0)
                    ->schema([
                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('year')
                                    ->label(__('app.label.year'))
                                    ->required(),

                                Textarea::make('text')
                                    ->label(__('app.label.description'))
                                    ->rows(3),
                            ]),

                        Fields::status(),
                    ]),

                SaveAction::make(self::class),
            ]);
    }
}
