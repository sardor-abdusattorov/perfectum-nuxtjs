<?php

namespace App\Filament\Resources\FreeNumberFilters\Schemas;

use App\Filament\Support\Fields;
use App\Models\FreeNumberFilter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FreeNumberFilterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.basic_information'))
                    ->schema([
                        Select::make('type')
                            ->label(__('app.label.filter_type'))
                            ->options(FreeNumberFilter::getTypeOptions())
                            ->required()
                            ->default(FreeNumberFilter::TYPE_PREFIX),

                        TextInput::make('name')
                            ->label(__('app.label.name'))
                            ->required(),

                        TextInput::make('value')
                            ->label(__('app.label.value'))
                            ->helperText(__('app.helper.filter_value'))
                            ->required(),

                        Fields::status(),
                    ]),
            ]);
    }
}
