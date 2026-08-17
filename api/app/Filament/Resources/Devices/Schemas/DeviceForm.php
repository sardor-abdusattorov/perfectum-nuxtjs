<?php

namespace App\Filament\Resources\Devices\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Support\Fields;
use App\Models\DeviceBrand;
use App\Models\DeviceCategory;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DeviceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.basic_information'))
                    ->schema([
                        Fields::category(DeviceCategory::class),

                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('app.label.name'))
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(Fields::slugPreview()),

                                Textarea::make('excerpt')
                                    ->label(__('app.label.excerpt'))
                                    ->rows(3),

                                Fields::editor('content')
                                    ->label(__('app.label.content')),
                            ]),

                        Fields::slug(),

                        Select::make('brand_id')
                            ->label(__('app.label.brand'))
                            ->helperText(__('app.helper.device_brand'))
                            ->options(DeviceBrand::options())
                            ->searchable(),

                        Fields::image('devices'),

                        TextInput::make('price')
                            ->label(__('app.label.price'))
                            ->helperText(__('app.helper.price'))
                            ->numeric()
                            ->minValue(0),

                        Toggle::make('in_stock')
                            ->label(__('app.label.in_stock'))
                            ->default(true),

                        Fields::sort(),

                        Fields::status(),
                    ]),

                Section::make(__('app.label.specs'))
                    ->description(__('app.helper.specs'))
                    ->schema([
                        Repeater::make('specs')
                            ->hiddenLabel()
                            ->addActionLabel(__('app.action.add'))
                            ->schema([
                                TranslatableTabs::make('spec_translations')
                                    ->schema([
                                        TextInput::make('label')
                                            ->label(__('app.label.name')),

                                        TextInput::make('value')
                                            ->label(__('app.label.value')),
                                    ]),
                            ])
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),
                    ]),
            ]);
    }
}
