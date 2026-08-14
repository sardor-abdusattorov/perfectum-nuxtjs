<?php

namespace App\Filament\Pages\Blocks;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\ContentBlockKey;
use App\Filament\Support\Fields;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;

/**
 * Every inner page opens with the same eyebrow, title and subtitle, so the
 * tab is written once and each manager only says which page it edits.
 */
abstract class PageHeroTab extends ContentTab
{
    public static function key(): ContentBlockKey
    {
        return ContentBlockKey::PageHero;
    }

    public static function make(): Tab
    {
        return Tab::make(__('app.section.page_hero'))
            ->schema([
                Section::make(__('app.label.section_texts'))
                    ->schema([
                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('page_hero.eyebrow')
                                    ->label(__('app.label.eyebrow')),

                                Fields::multiline('page_hero.title')
                                    ->label(__('app.label.title'))
                                    ->required(),

                                Fields::multiline('page_hero.subtitle')
                                    ->label(__('app.label.subtitle')),
                            ]),
                    ]),

                SaveAction::make(static::class),
            ]);
    }
}
