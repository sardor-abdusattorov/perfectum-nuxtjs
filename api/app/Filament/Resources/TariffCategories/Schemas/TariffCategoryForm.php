<?php

namespace App\Filament\Resources\TariffCategories\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Support\Fields;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TariffCategoryForm
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

                        Fields::sort(),

                        Fields::status(),

                        Toggle::make('in_catalog')
                            ->label(__('app.label.in_catalog'))
                            ->helperText(__('app.helper.in_catalog'))
                            ->default(true),
                    ]),
            ]);
    }
}
