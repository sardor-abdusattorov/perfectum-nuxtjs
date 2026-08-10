<?php

namespace App\Filament\Pages\Homepage;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\ContentBlockKey;
use App\Filament\Support\TabSaveAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;

class FeaturesTab extends ContentTab
{
    public static function key(): ContentBlockKey
    {
        return ContentBlockKey::Features;
    }

    public static function make(): Tab
    {
        return Tab::make(__('app.section.features'))
            ->schema([
                TranslatableTabs::make('translations')
                    ->schema([
                        TextInput::make('features.title')
                            ->label(__('app.label.title')),

                        TextInput::make('features.title_accent')
                            ->label(__('app.label.title_accent')),
                    ]),

                Section::make(__('app.label.dials'))
                    ->description(__('app.helper.dials'))
                    ->schema([
                        Repeater::make('features.dials')
                            ->hiddenLabel()
                            ->schema([
                                TranslatableTabs::make('dial_translations')
                                    ->schema([
                                        TextInput::make('caption')
                                            ->label(__('app.label.caption')),
                                    ]),

                                TextInput::make('from')
                                    ->label(__('app.label.value_from'))
                                    ->numeric(),

                                TextInput::make('to')
                                    ->label(__('app.label.value_to'))
                                    ->numeric(),

                                TextInput::make('max')
                                    ->label(__('app.label.value_max'))
                                    ->numeric(),

                                TextInput::make('unit')
                                    ->label(__('app.label.unit')),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),
                    ]),

                TabSaveAction::make('features', self::class),
            ]);
    }
}
