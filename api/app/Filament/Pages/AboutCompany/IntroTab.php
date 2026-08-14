<?php

namespace App\Filament\Pages\AboutCompany;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Filament\Pages\Blocks\ContentTab;
use App\Filament\Pages\Blocks\SaveAction;
use App\Filament\Support\Fields;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;

class IntroTab extends ContentTab
{
    public static function key(): ContentBlockKey
    {
        return ContentBlockKey::Intro;
    }

    public static function page(): PageKey
    {
        return PageKey::AboutCompany;
    }

    public static function make(): Tab
    {
        return Tab::make(__('app.section.intro'))
            ->schema([
                Section::make(__('app.label.section_texts'))
                    ->schema([
                        TranslatableTabs::make('translations')
                            ->schema([
                                Fields::editor('intro.content')
                                    ->label(__('app.label.content')),
                            ]),
                    ]),

                SaveAction::make(self::class),
            ]);
    }
}
