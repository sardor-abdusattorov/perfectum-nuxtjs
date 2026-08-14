<?php

namespace App\Filament\Pages\CdmaConnect;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Filament\Pages\Blocks\ContentTab;
use App\Filament\Pages\Blocks\SaveAction;
use App\Filament\Support\Fields;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;

class ArticleTab extends ContentTab
{
    public static function key(): ContentBlockKey
    {
        return ContentBlockKey::Intro;
    }

    public static function page(): PageKey
    {
        return PageKey::CdmaConnect;
    }

    public static function make(): Tab
    {
        return Tab::make(__('app.section.intro'))
            ->schema([
                Section::make(__('app.label.section_texts'))
                    ->schema([
                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('intro.date')
                                    ->label(__('app.label.published_at')),

                                TextInput::make('intro.title')
                                    ->label(__('app.label.title')),

                                Fields::editor('intro.content')
                                    ->label(__('app.label.content'))
                                    ->helperText(__('app.helper.cdma_article')),
                            ]),
                    ]),

                SaveAction::make(self::class),
            ]);
    }
}
