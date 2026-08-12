<?php

namespace App\Filament\Resources\TariffTypes\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Support\Fields;
use App\Models\TariffCategory;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TariffTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.basic_information'))
                    ->schema([
                        Fields::category(TariffCategory::class)
                            ->label(__('app.label.tariff_categories_single'))
                            ->helperText(__('app.helper.type_category')),

                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('app.label.name'))
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(Fields::slugPreview()),
                            ]),

                        Fields::slug(),

                        Fields::sort(),

                        Fields::status(),
                    ]),
            ]);
    }
}
