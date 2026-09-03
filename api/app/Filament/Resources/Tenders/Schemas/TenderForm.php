<?php

namespace App\Filament\Resources\Tenders\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\TenderState;
use App\Filament\Support\Fields;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TenderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.basic_information'))
                    ->schema([
                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('title')
                                    ->label(__('app.label.title'))
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(Fields::slugPreview()),

                                Fields::editor('content')
                                    ->label(__('app.label.content'))
                                    ->required(),
                            ]),

                        Fields::slug(),

                        Select::make('state')
                            ->label(__('app.label.tender_state'))
                            ->options(TenderState::getOptions())
                            ->default(TenderState::Open->value)
                            ->required(),

                        DatePicker::make('deadline_at')
                            ->label(__('app.label.deadline_at')),

                        Fields::files('tenders')
                            ->helperText(__('app.helper.tender_files')),

                        Fields::status(),
                    ]),
            ]);
    }
}
