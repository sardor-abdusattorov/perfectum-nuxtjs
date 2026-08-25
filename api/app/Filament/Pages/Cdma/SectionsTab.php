<?php

namespace App\Filament\Pages\Cdma;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\CdmaSection;
use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Filament\Pages\Blocks\ContentTab;
use App\Filament\Pages\Blocks\SaveAction;
use App\Filament\Support\Fields;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs\Tab;

class SectionsTab extends ContentTab
{
    public static function key(): ContentBlockKey
    {
        return ContentBlockKey::Sections;
    }

    public static function page(): PageKey
    {
        return PageKey::Cdma;
    }

    public static function make(): Tab
    {
        return Tab::make(__('app.section.sections'))
            ->schema([
                Repeater::make('sections.items')
                    ->label(__('app.label.sections'))
                    ->helperText(__('app.helper.cdma_sections'))
                    ->addActionLabel(__('app.action.add'))
                    ->schema([
                        Select::make('section')
                            ->label(__('app.label.cdma_section'))
                            ->options(CdmaSection::getSectionOptions())
                            ->native(false)
                            ->required()
                            ->distinct(),

                        TranslatableTabs::make('sections_translations')
                            ->schema([
                                TextInput::make('title')
                                    ->label(__('app.label.title'))
                                    ->required(),
                            ]),

                        Fields::status(),
                    ])
                    ->itemLabel(Fields::itemLabel('title'))
                    ->defaultItems(0)
                    ->reorderable()
                    ->collapsible(),

                SaveAction::make(self::class),
            ]);
    }
}
