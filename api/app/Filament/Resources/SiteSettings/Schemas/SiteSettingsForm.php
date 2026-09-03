<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SiteSettingsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.basic_information'))
                    ->description(__('app.label.site_settings_description'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('app.label.name'))
                            ->helperText(__('app.helper.unique_setting_identifier'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        TextInput::make('value')
                            ->label(__('app.label.value'))
                            ->helperText(__('app.helper.setting_value_pairs'))
                            ->maxLength(255),

                        Toggle::make('is_published')
                            ->label(__('app.label.show_on_site'))
                            ->helperText(__('app.helper.if_disabled_setting_not_used'))
                            ->default(true),
                    ]),
            ]);
    }
}
