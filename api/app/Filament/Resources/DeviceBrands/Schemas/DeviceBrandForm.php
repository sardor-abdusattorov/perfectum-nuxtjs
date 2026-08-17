<?php

namespace App\Filament\Resources\DeviceBrands\Schemas;

use App\Filament\Support\Fields;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DeviceBrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.basic_information'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('app.label.name'))
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(Fields::slugPreview())
                            ->maxLength(255),

                        Fields::slug(),

                        Fields::image('brands', 'logo')
                            ->label(__('app.label.logo'))
                            ->helperText(__('app.helper.device_brand_logo')),

                        ColorPicker::make('color')
                            ->label(__('app.label.color'))
                            ->helperText(__('app.helper.device_brand_color')),

                        Fields::sort(),

                        Fields::status(),
                    ]),
            ]);
    }
}
