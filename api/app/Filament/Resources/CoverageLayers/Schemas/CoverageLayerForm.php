<?php

namespace App\Filament\Resources\CoverageLayers\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Support\Fields;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CoverageLayerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.basic_information'))
                    ->schema([
                        TextInput::make('key')
                            ->label(__('app.label.key'))
                            ->helperText(__('app.helper.coverage_key'))
                            ->required()
                            ->alphaDash()
                            ->maxLength(20)
                            ->unique(ignoreRecord: true),

                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('app.label.name'))
                                    ->required(),
                            ]),

                        ColorPicker::make('color')
                            ->label(__('app.label.color'))
                            ->default('#e60000')
                            ->required(),

                        FileUpload::make('file')
                            ->label(__('app.label.coverage_file'))
                            ->helperText(__('app.helper.coverage_file'))
                            ->disk('public')
                            ->directory(fn (): string => 'uploads/coverage/'.now()->format('Y/m'))
                            ->visibility('public')
                            ->acceptedFileTypes(['application/zip', 'application/x-zip-compressed'])
                            ->downloadable()
                            ->maxSize(51200),

                        Fields::sort(),

                        Fields::status(),
                    ]),
            ]);
    }
}
