<?php

namespace App\Filament\Resources\Documents\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Support\Fields;
use App\Models\DocumentCategory;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.basic_information'))
                    ->schema([
                        Fields::category(DocumentCategory::class),

                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('app.label.name'))
                                    ->required(),

                                Fields::file('documents')
                                    ->helperText(__('app.helper.document_file')),
                            ]),

                        Fields::sort(),

                        Fields::status(),
                    ]),
            ]);
    }
}
