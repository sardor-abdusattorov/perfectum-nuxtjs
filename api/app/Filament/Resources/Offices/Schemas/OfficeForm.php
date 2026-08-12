<?php

namespace App\Filament\Resources\Offices\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
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
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.basic_information'))
                    ->schema([
                        Select::make('type')
                            ->label(__('app.label.office_type'))
                            ->helperText(__('app.helper.office_type'))
                            ->options(OfficeType::getOptions())
                            ->default(OfficeType::Office->value)
                            ->selectablePlaceholder(false)
                            ->required()
                            ->live(),

                        TextInput::make('name')
                            ->label(__('app.label.office_name'))
                            ->helperText(__('app.helper.office_name'))
                            ->maxLength(255)
                            ->required(fn (Get $get): bool => $get('type') === OfficeType::Dealer->value),

                        Fields::category(Region::class, 'region_id')
                            ->label(__('app.label.region_single'))
                            ->helperText(__('app.helper.office_region'))
                            ->required(),

                        Fields::network(),

                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('district')
                                    ->label(__('app.label.district')),

                                TextInput::make('address')
                                    ->label(__('app.label.address'))
                                    ->required(),
                            ]),

                        TextInput::make('phone')
                            ->label(__('app.label.phone'))
                            ->tel()
                            ->maxLength(255),

                        Grid::make(2)->schema([
                            TextInput::make('lat')
                                ->label(__('app.label.lat'))
                                ->helperText(__('app.helper.coordinates'))
                                ->numeric(),

                            TextInput::make('lng')
                                ->label(__('app.label.lng'))
                                ->numeric(),
                        ]),

                        Fields::sort(),

                        Fields::status(),
                    ]),
            ]);
    }
}
