<?php

namespace App\Filament\Pages\Homepage;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Filament\Pages\Blocks\ContentTab;
use App\Filament\Pages\Blocks\SaveAction;
use App\Filament\Support\Fields;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;

class FeaturesTab extends ContentTab
{
    public static function key(): ContentBlockKey
    {
        return ContentBlockKey::Features;
    }

    public static function page(): PageKey
    {
        return PageKey::Home;
    }

    public static function make(): Tab
    {
        return Tab::make(__('app.section.features'))
            ->schema([
                Section::make(__('app.label.section_texts'))
                    ->schema([
                        TranslatableTabs::make('translations')
                            ->schema([
                                Fields::multiline('features.title')
                                    ->label(__('app.label.title'))
                                    ->required(),

                                TextInput::make('features.link.label')
                                    ->label(__('app.label.link_label')),
                            ]),

                        TextInput::make('features.link.url')
                            ->label(__('app.label.url'))
                            ->helperText(__('app.helper.features_link')),
                    ]),

                Section::make(__('app.label.cards'))
                    ->schema([
                        Repeater::make('features.cards')
                            ->hiddenLabel()
                            ->addActionLabel(__('app.action.add'))
                            ->schema([
                                TranslatableTabs::make('card_translations')
                                    ->schema([
                                        TextInput::make('tag')
                                            ->label(__('app.label.tag')),

                                        Fields::multiline('title')
                                            ->label(__('app.label.title'))
                                            ->required(),

                                        Fields::multiline('text')
                                            ->label(__('app.label.description')),

                                        TextInput::make('link_label')
                                            ->label(__('app.label.link_label')),
                                    ]),

                                TextInput::make('url')
                                    ->label(__('app.label.url')),

                                Select::make('style')
                                    ->label(__('app.label.card_style'))
                                    ->options([
                                        'lag' => __('app.card_style.lag'),
                                        'smart' => __('app.card_style.smart'),
                                        'wire' => __('app.card_style.wire'),
                                        'pro' => __('app.card_style.pro'),
                                    ]),

                                Fields::status(),
                            ])
                            ->itemLabel(Fields::itemLabel('title'))
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),
                    ]),

                Section::make(__('app.label.dials'))
                    ->description(__('app.helper.dials'))
                    ->schema([
                        TranslatableTabs::make('speed_translations')
                            ->schema([
                                Fields::multiline('features.speed_text')
                                    ->label(__('app.label.speed_text')),

                                TextInput::make('features.speed_unit')
                                    ->label(__('app.label.unit')),
                            ]),

                        Repeater::make('features.dials')
                            ->hiddenLabel()
                            ->addActionLabel(__('app.action.add'))
                            ->schema([
                                TextInput::make('label')
                                    ->label(__('app.label.caption'))
                                    ->helperText(__('app.helper.dial_label')),

                                Select::make('color')
                                    ->label(__('app.label.dial_color'))
                                    ->options([
                                        'red' => __('app.color.red'),
                                        'orange' => __('app.color.orange'),
                                    ]),

                                TextInput::make('max')
                                    ->label(__('app.label.value_max'))
                                    ->numeric(),

                                TextInput::make('from')
                                    ->label(__('app.label.value_from'))
                                    ->numeric(),

                                TextInput::make('to')
                                    ->label(__('app.label.value_to'))
                                    ->numeric(),
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),
                    ]),

                SaveAction::make(self::class),
            ]);
    }
}
