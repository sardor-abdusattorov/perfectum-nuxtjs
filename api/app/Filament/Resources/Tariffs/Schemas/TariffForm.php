<?php

namespace App\Filament\Resources\Tariffs\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\CategoryType;
use App\Filament\Support\CategorySelect;
use App\Filament\Support\ImageUpload;
use App\Filament\Support\MultilineText;
use App\Filament\Support\SlugInput;
use App\Filament\Support\StatusToggle;
use App\Filament\Support\TextEditor;
use App\Filament\Support\Translated;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TariffForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.basic_information'))
                    ->schema([
                        CategorySelect::make(CategoryType::Tariff),

                        CategorySelect::make(CategoryType::TariffType, 'type_id')
                            ->label(__('app.label.tariff_type'))
                            ->helperText(__('app.helper.tariff_type')),

                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('app.label.name'))
                                    ->required(Translated::required())
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, Get $get, ?string $state, string $operation) => $operation === 'create' && blank($get('slug'))
                                        ? $set('slug', Str::slug($state ?? ''))
                                        : null),

                                MultilineText::make('lead')
                                    ->label(__('app.label.lead_text'))
                                    ->helperText(__('app.helper.tariff_lead')),
                            ]),

                        SlugInput::make(),

                        StatusToggle::make(),
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
                            ]),
                    ]),

                Section::make(__('app.label.tariff_features'))
                    ->description(__('app.helper.tariff_features'))
                    ->schema([
                        Repeater::make('features')
                            ->hiddenLabel()
                            ->addActionLabel(__('app.action.add'))
                            ->schema([
                                ImageUpload::make('tariffs', 'icon')
                                    ->label(__('app.label.icon'))
                                    ->helperText(__('app.helper.tariff_feature_icon'))
                                    ->imageEditor(false),

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
                            ->itemLabel(fn (array $state): ?string => is_array($state['title'] ?? null)
                                ? (string) reset($state['title'])
                                : null)
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),
                    ]),

                Section::make(__('app.label.tariff_connect'))
                    ->description(__('app.helper.tariff_connect'))
                    ->schema([
                        ImageUpload::make('tariffs', 'modal_image')
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
                                ImageUpload::make('tariffs', 'icon')
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
                            ->itemLabel(fn (array $state): ?string => is_array($state['name'] ?? null)
                                ? (string) reset($state['name'])
                                : null)
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),
                    ]),

                Section::make(__('app.label.additionally'))
                    ->schema([
                        TranslatableTabs::make('terms_translations')
                            ->schema([
                                TextEditor::make('terms')
                                    ->label(__('app.label.terms'))
                                    ->helperText(__('app.helper.terms')),
                            ]),

                        ImageUpload::make('tariffs'),

                        Toggle::make('is_featured')
                            ->label(__('app.label.is_featured'))
                            ->helperText(__('app.helper.is_featured')),

                        Toggle::make('is_archived')
                            ->label(__('app.label.is_archived'))
                            ->helperText(__('app.helper.is_archived')),

                        TextInput::make('sort')
                            ->label(__('app.label.sort'))
                            ->helperText(__('app.helper.sort'))
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ]),
            ]);
    }
}
