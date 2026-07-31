<?php

namespace App\Filament\Resources\SiteTranslations\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SiteTranslationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.basic_information'))
                    ->description(__('app.label.key_and_translations_settings'))
                    ->schema([
                        Hidden::make('category')
                            ->default('app'),

                        TextInput::make('key')
                            ->label(__('app.label.key'))
                            ->helperText(__('app.helper.unique_translation_identifier'))
                            ->required(),

                        TranslatableTabs::make('translations')
                            ->schema([
                                Textarea::make('value')
                                    ->label(__('app.label.value'))
                                    ->rows(6)
                                    ->helperText(__('app.helper.text_displayed_on_site'))
                                    ->required(),
                            ]),

                        Toggle::make('is_published')
                            ->label(__('app.label.show_on_site'))
                            ->helperText(__('app.helper.if_disabled_translation_not_used'))
                            ->default(true),
                    ]),
            ]);
    }
}
