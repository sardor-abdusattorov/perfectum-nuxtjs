<?php

namespace App\Filament\Resources\ApplicationStatuses\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Support\Fields;
use App\Models\ApplicationStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ApplicationStatusForm
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
                                TextInput::make('name')
                                    ->label(__('app.label.name'))
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(Fields::slugPreview()),
                            ]),

                        Fields::slug(),

                        Select::make('color')
                            ->label(__('app.label.color'))
                            ->options(ApplicationStatus::colorOptions())
                            ->default('gray')
                            ->native(false)
                            ->required(),

                        Toggle::make('is_default')
                            ->label(__('app.label.is_default_status'))
                            ->helperText(__('app.helper.is_default_status')),

                        Fields::sort(),

                        Fields::status(),
                    ]),
            ]);
    }
}
