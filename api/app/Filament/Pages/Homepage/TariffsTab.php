<?php

namespace App\Filament\Pages\Homepage;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\ContentBlockKey;
use App\Filament\Support\MultilineText;
use App\Filament\Support\StatusToggle;
use App\Filament\Support\TabSaveAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;

class TariffsTab extends ContentTab
{
    public static function key(): ContentBlockKey
    {
        return ContentBlockKey::Tariffs;
    }

    public static function make(): Tab
    {
        return Tab::make(__('app.section.tariffs'))
            ->schema([
                Section::make(__('app.label.section_texts'))
                    ->description(__('app.helper.section_texts_only'))
                    ->schema([
                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('tariffs.eyebrow')
                                    ->label(__('app.label.eyebrow')),

                                MultilineText::make('tariffs.title')
                                    ->label(__('app.label.title')),
                            ]),
                    ]),

                Section::make(__('app.label.tariff_groups'))
                    ->description(__('app.helper.tariff_groups'))
                    ->schema([
                        Repeater::make('tariffs.groups')
                            ->hiddenLabel()
                            ->addActionLabel(__('app.action.add'))
                            ->schema([
                                TextInput::make('slug')
                                    ->label(__('app.label.key'))
                                    ->helperText(__('app.helper.tariff_slug')),

                                TranslatableTabs::make('group_translations')
                                    ->schema([
                                        TextInput::make('label')
                                            ->label(__('app.label.name')),
                                    ]),

                                StatusToggle::make(),
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['slug'] ?? null)
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),
                    ]),

                Section::make(__('app.label.tariff_filters'))
                    ->description(__('app.helper.tariff_filters'))
                    ->schema([
                        Repeater::make('tariffs.filters')
                            ->hiddenLabel()
                            ->addActionLabel(__('app.action.add'))
                            ->schema([
                                TextInput::make('slug')
                                    ->label(__('app.label.key'))
                                    ->helperText(__('app.helper.tariff_slug')),

                                TranslatableTabs::make('filter_translations')
                                    ->schema([
                                        TextInput::make('label')
                                            ->label(__('app.label.name')),
                                    ]),

                                StatusToggle::make(),
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['slug'] ?? null)
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),
                    ]),

                TabSaveAction::make('tariffs', self::class),
            ]);
    }
}
