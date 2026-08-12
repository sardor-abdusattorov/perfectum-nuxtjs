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
use Filament\Schemas\Components\Utilities\Get;

class HeroTab extends ContentTab
{
    public static function key(): ContentBlockKey
    {
        return ContentBlockKey::Hero;
    }

    public static function make(): Tab
    {
        return Tab::make(__('app.section.hero'))
            ->schema([
                Section::make(__('app.label.slides'))
                    ->description(__('app.helper.hero_slides'))
                    ->schema([
                        Repeater::make('hero.slides')
                            ->hiddenLabel()
                            ->addActionLabel(__('app.action.add'))
                            ->schema([
                                TranslatableTabs::make('slide_translations')
                                    ->schema([
                                        TextInput::make('description')
                                            ->label(__('app.label.eyebrow')),

                                        Fields::editor('title')
                                            ->label(__('app.label.title'))
                                            ->helperText(__('app.helper.hero_title'))
                                            ->extraInputAttributes([
                                                'style' => 'min-height: 8rem; max-height: 30vh; overflow-y: auto;',
                                            ])
                                            ->required(),

                                        Fields::multiline('lead')
                                            ->label(__('app.label.lead_text'))
                                            ->helperText(__('app.helper.hero_lead')),
                                    ]),

                                Fields::image('content-blocks', 'image')
                                    ->label(__('app.label.image')),

                                Repeater::make('buttons')
                                    ->label(__('app.label.buttons'))
                                    ->addActionLabel(__('app.action.add'))
                                    ->schema([
                                        TranslatableTabs::make('button_translations')
                                            ->schema([
                                                TextInput::make('label')
                                                    ->label(__('app.label.button_label')),
                                            ]),

                                        TextInput::make('url')
                                            ->label(__('app.label.url')),

                                        Select::make('style')
                                            ->label(__('app.label.button_style'))
                                            ->options([
                                                'primary' => __('app.button_style.primary'),
                                                'secondary' => __('app.button_style.secondary'),
                                            ])
                                            ->default('primary'),

                                        Fields::status(),
                                    ])
                                    ->itemLabel(Fields::itemLabel('label'))
                                    ->defaultItems(0)
                                    ->reorderable()
                                    ->collapsible(),

                                Fields::status('show_aside')
                                    ->label(__('app.label.show_aside'))
                                    ->helperText(__('app.helper.show_aside')),

                                Fields::status('show_gauge')
                                    ->label(__('app.label.show_gauge'))
                                    ->helperText(__('app.helper.show_gauge'))
                                    ->live(),

                                TextInput::make('gauge_value')
                                    ->label(__('app.label.gauge_value'))
                                    ->helperText(__('app.helper.gauge_value'))
                                    ->visible(fn (Get $get): bool => (bool) $get('show_gauge')),

                                Fields::status(),
                            ])
                            ->itemLabel(Fields::itemLabel('title'))
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),
                    ]),

                SaveAction::make(self::class),
            ]);
    }
}
