<?php

namespace App\Filament\Resources\Regions\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Support\Fields;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RegionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.basic_information'))
                    ->schema([
                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('app.label.name'))
                                    ->required(),
                            ]),

                        Fields::network(),

                        TextInput::make('latitude')
                            ->label(__('app.label.latitude'))
                            ->helperText(__('app.helper.region_coordinates'))
                            ->numeric(),

                        TextInput::make('longitude')
                            ->label(__('app.label.longitude'))
                            ->numeric(),

                        Fields::sort(),

                        Fields::status(),
                    ]),
            ]);
    }
}
