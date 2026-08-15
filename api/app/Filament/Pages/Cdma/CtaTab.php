<?php

namespace App\Filament\Pages\Cdma;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Filament\Pages\Blocks\ContentTab;
use App\Filament\Pages\Blocks\SaveAction;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;

class CtaTab extends ContentTab
{
    public static function key(): ContentBlockKey
    {
        return ContentBlockKey::Cta;
    }

    public static function page(): PageKey
    {
        return PageKey::Cdma;
    }

    public static function make(): Tab
    {
        return Tab::make(__('app.section.cta'))
            ->schema([
                Section::make(__('app.label.section_texts'))
                    ->schema([
                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('cta.kicker')
                                    ->label(__('app.label.eyebrow')),

                                TextInput::make('cta.title')
                                    ->label(__('app.label.title'))
                                    ->required(),

                                TextInput::make('cta.text')
                                    ->label(__('app.label.text')),

                                TextInput::make('cta.primary_label')
                                    ->label(__('app.label.cta_primary')),

                                TextInput::make('cta.ghost_label')
                                    ->label(__('app.label.cta_ghost')),
                            ]),

                        Grid::make(2)->schema([
                            TextInput::make('cta.primary_url')
                                ->label(__('app.label.cta_primary_url')),

                            TextInput::make('cta.ghost_url')
                                ->label(__('app.label.cta_ghost_url')),
                        ]),
                    ]),

                SaveAction::make(self::class),
            ]);
    }
}
