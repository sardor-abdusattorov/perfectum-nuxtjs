<?php

namespace App\Filament\Resources\Actions\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\Network;
use App\Filament\Support\Fields;
use App\Models\ActionCategory;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ActionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.basic_information'))
                    ->schema([
                        Fields::network()
                            ->options(collect(Network::getOptions())->except(Network::Both->value)->all())
                            ->default(Network::FiveG->value)
                            ->live(),

                        Fields::category(ActionCategory::class)
                            ->visible(fn (Get $get): bool => $get('network') !== Network::Cdma->value)
                            ->required(fn (Get $get): bool => $get('network') !== Network::Cdma->value),

                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('title')
                                    ->label(__('app.label.title'))
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(Fields::slugPreview()),

                                TextInput::make('badge')
                                    ->label(__('app.label.badge'))
                                    ->helperText(__('app.helper.badge')),

                                Textarea::make('excerpt')
                                    ->label(__('app.label.excerpt'))
                                    ->helperText(__('app.helper.excerpt'))
                                    ->rows(3),

                                Fields::editor('content')
                                    ->label(__('app.label.content'))
                                    ->required(),
                            ]),

                        Fields::slug(),

                        Fields::image('actions', 'preview_image')
                            ->label(__('app.label.preview_image'))
                            ->helperText(__('app.helper.preview_image')),

                        Fields::image('actions', 'main_image')
                            ->label(__('app.label.main_image'))
                            ->helperText(__('app.helper.main_image')),

                        DatePicker::make('starts_at')
                            ->label(__('app.label.starts_at')),

                        DatePicker::make('ends_at')
                            ->label(__('app.label.ends_at'))
                            ->helperText(__('app.helper.ends_at'))
                            ->afterOrEqual('starts_at'),

                        Fields::status(),
                    ]),
            ]);
    }
}
