<?php

namespace App\Filament\Resources\TariffCategories\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Support\Fields;
use Filament\Forms\Components\TextInput;
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
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(Fields::slugPreview()),
                            ]),

                        Fields::slug(),

                        TextInput::make('code')
                            ->label(__('app.label.code'))
                            ->helperText(__('app.helper.category_code'))
                            ->maxLength(32),

                        Fields::network(),

                        Fields::sort(),

                        Fields::status(),
                    ]),
            ]);
    }
}
