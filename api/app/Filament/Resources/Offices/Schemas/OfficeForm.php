<?php

namespace App\Filament\Resources\Offices\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\Network;
use App\Enums\OfficeType;
use App\Filament\Support\Fields;
use App\Models\Region;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class OfficeForm
{
    public static function configure(Schema $schema): Schema
    {
        $isCdma = fn (Get $get): bool => $get('network') === Network::Cdma->value;

        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.basic_information'))
                    ->schema([
                        Fields::network()
                            ->live()
                            ->helperText(__('app.helper.office_network')),

                        Select::make('type')
                            ->label(__('app.label.office_type'))
                            ->helperText(__('app.helper.office_type'))
                            ->options(OfficeType::getOptions())
                            ->default(OfficeType::Office->value)
                            ->selectablePlaceholder(false)
                            ->required()
                            ->live()
                            ->hidden($isCdma),

                        TextInput::make('name')
                            ->label(__('app.label.office_name'))
                            ->helperText(__('app.helper.office_name'))
                            ->maxLength(255)
                            ->required(fn (Get $get): bool => $get('type') === OfficeType::Dealer->value)
                            ->hidden($isCdma),

                        Fields::category(Region::class, 'region_id')
                            ->label(__('app.label.region_single'))
                            ->helperText(__('app.helper.office_region'))
                            ->required(),

                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('district')
                                    ->label(__('app.label.district')),

                                TextInput::make('address')
                                    ->label(__('app.label.address'))
                                    ->required(fn (Get $get): bool => ! $isCdma($get)),
                            ])
                            ->hidden($isCdma),

                        TextInput::make('phone')
                            ->label(__('app.label.phone'))
                            ->tel()
                            ->maxLength(255)
                            ->hidden($isCdma),

                        Grid::make(2)->schema([
                            TextInput::make('lat')
                                ->label(__('app.label.lat'))
                                ->helperText(__('app.helper.coordinates'))
                                ->numeric(),

                            TextInput::make('lng')
                                ->label(__('app.label.lng'))
                                ->numeric(),
                        ])->hidden($isCdma),

                        TextInput::make('dealers_count')
                            ->label(__('app.label.dealers_count'))
                            ->helperText(__('app.helper.dealers_count'))
                            ->numeric()
                            ->minValue(0)
                            ->visible($isCdma),

                        TranslatableTabs::make('content_translations')
                            ->schema([
                                Fields::editor('content')
                                    ->label(__('app.label.content'))
                                    ->helperText(__('app.helper.dealer_content')),
                            ])
                            ->visible($isCdma),

                        Fields::sort(),

                        Fields::status(),
                    ]),
            ]);
    }
}
