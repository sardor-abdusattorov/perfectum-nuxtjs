<?php

namespace App\Filament\Pages\Homepage;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\ContentBlockKey;
use App\Filament\Support\TabSaveAction;
use Filament\Forms\Components\TextInput;
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
            ->description(__('app.helper.section_texts_only'))
            ->schema([
                TranslatableTabs::make('translations')
                    ->schema([
                        TextInput::make('tariffs.eyebrow')
                            ->label(__('app.label.eyebrow')),

                        TextInput::make('tariffs.title')
                            ->label(__('app.label.title')),

                        TextInput::make('tariffs.all_label')
                            ->label(__('app.label.all_link_label')),
                    ]),

                TabSaveAction::make('tariffs', self::class),
            ]);
    }
}
