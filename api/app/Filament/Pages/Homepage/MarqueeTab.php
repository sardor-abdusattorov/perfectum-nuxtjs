<?php

namespace App\Filament\Pages\Homepage;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\ContentBlockKey;
use App\Filament\Support\Fields;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
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
                Section::make(__('app.label.marquee_items'))
                    ->description(__('app.helper.marquee_items'))
                    ->schema([
                        Repeater::make('marquee.items')
                            ->hiddenLabel()
                            ->addActionLabel(__('app.action.add'))
                            ->schema([
                                TranslatableTabs::make('item_translations')
                                    ->schema([
                                        TextInput::make('text')
                                            ->label(__('app.label.text')),
                                    ]),

                                Fields::image('content-blocks', 'image')
                                    ->label(__('app.label.image'))
                                    ->helperText(__('app.helper.marquee_item_image')),

                                Fields::status(),
                            ])
                            ->itemLabel(Fields::itemLabel('text'))
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),
                    ]),

                SaveAction::make(self::class),
            ]);
    }
}
