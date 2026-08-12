<?php

namespace App\Filament\Resources\PageSettings\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\PageKey;
use App\Filament\Support\Fields;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PageSettingsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.seo'))
                    ->schema([
                        Select::make('key')
                            ->label(__('app.label.page'))
                            ->helperText(__('app.helper.page_key'))
                            ->options(PageKey::getOptions())
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->selectablePlaceholder(false)
                            ->searchable(),

                        TranslatableTabs::make('seo_translations')
                            ->schema([
                                TextInput::make('meta_title')
                                    ->label(__('app.label.meta_title'))
                                    ->helperText(__('app.helper.meta_title'))
                                    ->maxLength(120),

                                Textarea::make('meta_description')
                                    ->label(__('app.label.meta_description'))
                                    ->helperText(__('app.helper.meta_description'))
                                    ->rows(3)
                                    ->maxLength(200),
                            ]),

                        Fields::image('seo', 'og_image')
                            ->label(__('app.label.og_image'))
                            ->helperText(__('app.helper.og_image')),

                        Toggle::make('is_indexed')
                            ->label(__('app.label.is_indexed'))
                            ->helperText(__('app.helper.is_indexed'))
                            ->default(true),
                    ]),
            ]);
    }
}
