<?php

namespace App\Filament\Pages\Homepage;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\ContentBlockKey;
use App\Filament\Support\TabSaveAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;

class ChooseTab extends ContentTab
{
    public static function key(): ContentBlockKey
    {
        return ContentBlockKey::Choose;
    }

    public static function make(): Tab
    {
        return Tab::make(__('app.section.choose'))
            ->schema([
                TranslatableTabs::make('translations')
                    ->schema([
                        TextInput::make('choose.title')
                            ->label(__('app.label.title')),

                        TextInput::make('choose.all_label')
                            ->label(__('app.label.all_link_label')),
                    ]),

                Section::make(__('app.label.cards'))
                    ->schema([
                        Repeater::make('choose.cards')
                            ->hiddenLabel()
                            ->schema([
                                TranslatableTabs::make('card_translations')
                                    ->schema([
                                        TextInput::make('category')
                                            ->label(__('app.label.category')),

                                        TextInput::make('name')
                                            ->label(__('app.label.name')),
                                    ]),

                                TextInput::make('url')
                                    ->label(__('app.label.url')),

                                Select::make('color')
                                    ->label(__('app.label.card_color'))
                                    ->options([
                                        'red' => __('app.color.red'),
                                        'scarlet' => __('app.color.scarlet'),
                                        'black' => __('app.color.black'),
                                        'gray' => __('app.color.gray'),
                                    ]),
                            ])
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),
                    ]),

                TabSaveAction::make('choose', self::class),
            ]);
    }
}
