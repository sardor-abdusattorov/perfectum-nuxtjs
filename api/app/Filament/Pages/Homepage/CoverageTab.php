<?php

namespace App\Filament\Pages\Homepage;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\ContentBlockKey;
use App\Filament\Support\MultilineText;
use App\Filament\Support\StatusToggle;
use App\Filament\Support\TabSaveAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;

class CoverageTab extends ContentTab
{
    public static function key(): ContentBlockKey
    {
        return ContentBlockKey::Coverage;
    }

    public static function make(): Tab
    {
        return Tab::make(__('app.section.coverage'))
            ->schema([
                Section::make(__('app.label.section_texts'))
                    ->schema([
                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('coverage.title')
                                    ->label(__('app.label.title')),

                                MultilineText::make('coverage.subtitle')
                                    ->label(__('app.label.subtitle')),
                            ]),
                    ]),

                Section::make(__('app.label.cities'))
                    ->description(__('app.helper.coverage_cities'))
                    ->schema([
                        Repeater::make('coverage.cities')
                            ->hiddenLabel()
                            ->addActionLabel(__('app.action.add'))
                            ->schema([
                                TranslatableTabs::make('city_translations')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label(__('app.label.name')),

                                        TextInput::make('status')
                                            ->label(__('app.label.coverage_status')),
                                    ]),

                                Toggle::make('active')
                                    ->label(__('app.label.coverage_active'))
                                    ->helperText(__('app.helper.coverage_active')),

                                StatusToggle::make(),
                            ])
                            ->itemLabel(fn (array $state): ?string => is_array($state['name'] ?? null)
                                ? (string) reset($state['name'])
                                : null)
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),
                    ]),

                TabSaveAction::make('coverage', self::class),
            ]);
    }
}
