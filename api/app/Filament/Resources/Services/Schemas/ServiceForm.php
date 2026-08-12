<?php

namespace App\Filament\Resources\Services\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\CategoryType;
use App\Filament\Support\Fields;
use App\Filament\Support\Translated;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.basic_information'))
                    ->schema([
                        Fields::category(CategoryType::Service),

                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('app.label.name'))
                                    ->required(Translated::required())
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(Fields::slugPreview()),

                                Textarea::make('excerpt')
                                    ->label(__('app.label.excerpt'))
                                    ->helperText(__('app.helper.service_excerpt'))
                                    ->rows(3),

                                Fields::multiline('lead')
                                    ->label(__('app.label.lead_text'))
                                    ->helperText(__('app.helper.service_lead')),

                                Fields::editor('content')
                                    ->label(__('app.label.content')),
                            ]),

                        Fields::slug(),

                        Fields::status(),
                    ]),

                Section::make(__('app.label.service_summary'))
                    ->description(__('app.helper.service_summary'))
                    ->schema([
                        TextInput::make('ussd')
                            ->label(__('app.label.ussd'))
                            ->helperText(__('app.helper.service_ussd')),

                        TranslatableTabs::make('price_translations')
                            ->schema([
                                TextInput::make('price')
                                    ->label(__('app.label.price'))
                                    ->helperText(__('app.helper.service_price')),
                            ]),

                        Repeater::make('facts')
                            ->label(__('app.label.service_facts'))
                            ->addActionLabel(__('app.action.add'))
                            ->schema([
                                TranslatableTabs::make('fact_translations')
                                    ->schema([
                                        TextInput::make('label')
                                            ->label(__('app.label.caption')),

                                        TextInput::make('value')
                                            ->label(__('app.label.value')),
                                    ]),
                            ])
                            ->itemLabel(Translated::itemLabel('label'))
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),
                    ]),

                Section::make(__('app.label.service_steps'))
                    ->description(__('app.helper.service_steps'))
                    ->schema([
                        Repeater::make('steps')
                            ->hiddenLabel()
                            ->addActionLabel(__('app.action.add'))
                            ->schema([
                                TranslatableTabs::make('step_translations')
                                    ->schema([
                                        TextInput::make('text')
                                            ->label(__('app.label.text')),
                                    ]),

                                TextInput::make('code')
                                    ->label(__('app.label.ussd'))
                                    ->helperText(__('app.helper.service_step_code')),
                            ])
                            ->itemLabel(Translated::itemLabel('text'))
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),
                    ]),

                Section::make(__('app.label.additionally'))
                    ->schema([
                        Fields::image('services', 'icon')
                            ->label(__('app.label.icon'))
                            ->helperText(__('app.helper.service_icon'))
                            ->imageEditor(false),

                        Fields::image('services'),

                        Toggle::make('is_featured')
                            ->label(__('app.label.is_featured'))
                            ->helperText(__('app.helper.service_featured')),

                        Fields::sort(),
                    ]),
            ]);
    }
}
