<?php

namespace App\Filament\Pages\Homepage;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\ContentBlockKey;
use App\Filament\Support\Fields;
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
                Section::make(__('app.label.section_texts'))
                    ->schema([
                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('choose.title')
                                    ->label(__('app.label.title'))
                                    ->required(),

                                TextInput::make('choose.link.label')
                                    ->label(__('app.label.link_label')),
                            ]),

                        TextInput::make('choose.link.url')
                            ->label(__('app.label.url'))
                            ->helperText(__('app.helper.choose_link')),
                    ]),

                Section::make(__('app.label.cards'))
                    ->schema([
                        Repeater::make('choose.cards')
                            ->hiddenLabel()
                            ->addActionLabel(__('app.action.add'))
                            ->schema([
                                TranslatableTabs::make('card_translations')
                                    ->schema([
                                        TextInput::make('category')
                                            ->label(__('app.label.category')),

                                        Fields::multiline('name')
                                            ->label(__('app.label.name'))
                                            ->required(),
                                    ]),

                                TextInput::make('url')
                                    ->label(__('app.label.url')),

                                Select::make('color')
                                    ->label(__('app.label.card_color'))
                                    ->options([
                                        'red' => __('app.color.red'),
                                        'scarlet' => __('app.color.scarlet'),
                                        'dark' => __('app.color.dark'),
                                        'ruby' => __('app.color.ruby'),
                                    ]),

                                Fields::status(),
                            ])
                            ->itemLabel(Fields::itemLabel('name'))
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),
                    ]),

                SaveAction::make(self::class),
            ]);
    }
}
