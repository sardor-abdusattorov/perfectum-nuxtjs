<?php

namespace App\Filament\Resources\TariffFiles\Schemas;

use App\Filament\Support\Fields;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TariffFileForm
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
                            ->helperText(__('app.helper.tariff_file_name'))
                            ->required()
                            ->maxLength(255),

                        FileUpload::make('file')
                            ->label(__('app.label.file'))
                            ->disk('public')
                            ->directory(fn (): string => 'uploads/tariff-files/'.now()->format('Y/m'))
                            ->visibility('public')
                            ->downloadable()
                            ->maxSize(20480)
                            ->required(),

                        Fields::sort(),

                        Fields::status(),
                    ]),
            ]);
    }
}
