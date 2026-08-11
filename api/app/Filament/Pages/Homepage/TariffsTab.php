<?php

namespace App\Filament\Pages\Homepage;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\ContentBlockKey;
use App\Filament\Support\MultilineText;
use App\Filament\Support\TabSaveAction;
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

                TabSaveAction::make(self::class),
            ]);
    }
}
