<?php

namespace App\Filament\Resources\Tariffs\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Support\Fields;
use App\Models\TariffCategory;
use App\Models\TariffType;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TariffForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.basic_information'))
                    ->schema([
                        Fields::category(TariffCategory::class),

                        Fields::category(TariffType::class, 'type_id')
                            ->label(__('app.label.tariff_type'))
                            ->helperText(__('app.helper.tariff_type')),

                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('app.label.name'))
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(Fields::slugPreview()),
                            ]),

                        Fields::slug(),

                        Fields::status(),
                    ]),

                Section::make(__('app.label.price'))
                    ->schema([
                        TextInput::make('price')
                            ->label(__('app.label.price_value'))
                            ->helperText(__('app.helper.price_value')),

                        TranslatableTabs::make('price_translations')
                            ->schema([
                                TextInput::make('price_currency')
                                    ->label(__('app.label.price_currency')),

                                TextInput::make('price_period')
                                    ->label(__('app.label.price_period'))
                                    ->helperText(__('app.helper.price_period')),

                                TextInput::make('connection_cost')
                                    ->label(__('app.label.connection_cost'))
                                    ->helperText(__('app.helper.connection_cost')),
                            ]),
                    ]),

                Section::make(__('app.label.tariff_features'))
                    ->description(__('app.helper.tariff_features'))
                    ->schema([
                        Repeater::make('features')
                            ->hiddenLabel()
                            ->addActionLabel(__('app.action.add'))
                            ->schema([
                                Fields::featureIcon(),

                                TranslatableTabs::make('feature_translations')
                                    ->schema([
                                        TextInput::make('title')
                                            ->label(__('app.label.title'))
                                            ->helperText(__('app.helper.tariff_feature_title')),

                                        TextInput::make('note')
                                            ->label(__('app.label.note'))
                                            ->helperText(__('app.helper.tariff_feature_note')),
                                    ]),
                            ])
                            ->itemLabel(Fields::itemLabel('title'))
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),
                    ]),

                Section::make(__('app.label.tariff_connect'))
                    ->description(__('app.helper.tariff_connect'))
                    ->schema([
                        Fields::image('tariffs', 'modal_image')
                            ->label(__('app.label.modal_image'))
                            ->helperText(__('app.helper.modal_image'))
                            ->imageEditor(false),

                        TextInput::make('ussd')
                            ->label(__('app.label.ussd'))
                            ->helperText(__('app.helper.ussd')),

                        Repeater::make('buttons')
                            ->label(__('app.label.connect_buttons'))
                            ->addActionLabel(__('app.action.add'))
                            ->schema([
                                Fields::image('tariffs', 'icon')
                                    ->label(__('app.label.icon'))
                                    ->imageEditor(false),

                                TranslatableTabs::make('button_translations')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label(__('app.label.button_label')),
                                    ]),

                                Select::make('type')
                                    ->label(__('app.label.link_type'))
                                    ->options([
                                        'link' => __('app.link_type.link'),
                                        'tel' => __('app.link_type.tel'),
                                    ])
                                    ->default('link'),

                                TextInput::make('url')
                                    ->label(__('app.label.url')),
                            ])
                            ->itemLabel(Fields::itemLabel('name'))
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),
                    ]),

                Section::make(__('app.label.additionally'))
                    ->schema([
                        Repeater::make('descriptions')
                            ->label(__('app.label.tariff_descriptions'))
                            ->helperText(__('app.helper.tariff_descriptions'))
                            ->addActionLabel(__('app.action.add'))
                            ->schema([
                                TranslatableTabs::make('description_translations')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label(__('app.label.title'))
                                            ->required(),

                                        Fields::editor('content')
                                            ->label(__('app.label.content')),
                                    ]),
                            ])
                            ->itemLabel(Fields::itemLabel('name'))
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),

                        Fields::image('tariffs'),

                        Toggle::make('is_featured')
                            ->label(__('app.label.is_featured'))
                            ->helperText(__('app.helper.is_featured')),

                        Toggle::make('is_archived')
                            ->label(__('app.label.is_archived'))
                            ->helperText(__('app.helper.is_archived')),

                        Fields::sort(),
                    ]),
            ]);
    }
}
