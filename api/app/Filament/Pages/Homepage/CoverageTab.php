<?php

namespace App\Filament\Pages\Homepage;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\ContentBlockKey;
use App\Filament\Support\ImageUpload;
use App\Filament\Support\TabSaveAction;
use Filament\Forms\Components\TextInput;
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
                    ->description(__('app.helper.section_texts_only'))
                    ->schema([
                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('coverage.title')
                                    ->label(__('app.label.title')),

                                TextInput::make('coverage.subtitle')
                                    ->label(__('app.label.subtitle')),

                                TextInput::make('coverage.all_label')
                                    ->label(__('app.label.all_link_label')),
                            ]),

                        ImageUpload::make('content-blocks', 'coverage.map')
                            ->label(__('app.label.map_image')),
                    ]),

                TabSaveAction::make('coverage', self::class),
            ]);
    }
}
