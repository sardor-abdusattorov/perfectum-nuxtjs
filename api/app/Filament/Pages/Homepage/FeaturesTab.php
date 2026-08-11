<?php

namespace App\Filament\Pages\Homepage;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\ContentBlockKey;
use App\Filament\Support\MultilineText;
use App\Filament\Support\StatusToggle;
use App\Filament\Support\TabSaveAction;
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

    public static function make(): Tab
    {
        return Tab::make(__('app.section.features'))
            ->schema([
                Section::make(__('app.label.section_texts'))
                    ->schema([
                        TranslatableTabs::make('translations')
                            ->schema([
                                MultilineText::make('features.title')
                                    ->label(__('app.label.title')),

                                TextInput::make('features.title_accent')
                                    ->label(__('app.label.title_accent')),

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

                                        MultilineText::make('title')
                                            ->label(__('app.label.title')),

                                        MultilineText::make('text')
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

                                StatusToggle::make(),
                            ])
                            ->itemLabel(fn (array $state): ?string => is_array($state['title'] ?? null)
                                ? (string) reset($state['title'])
                                : null)
                            ->columns(2)
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),
                    ]),

                Section::make(__('app.label.dials'))
                    ->description(__('app.helper.dials'))
                    ->schema([
                        TranslatableTabs::make('speed_translations')
                            ->schema([
                                MultilineText::make('features.speed_text')
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

                                StatusToggle::make(),
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                            ->columns(3)
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),
                    ]),

                TabSaveAction::make('features', self::class),
            ]);
    }
}
