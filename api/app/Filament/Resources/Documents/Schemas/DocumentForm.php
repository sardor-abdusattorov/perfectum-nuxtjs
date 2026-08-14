<?php

namespace App\Filament\Resources\Documents\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Support\Fields;
use App\Models\DocumentCategory;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
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
                            ]),

                        Repeater::make('files')
                            ->relationship()
                            ->label(__('app.label.document_files'))
                            ->schema([
                                Select::make('language')
                                    ->label(__('app.label.language'))
                                    ->options(Fields::localeOptions())
                                    ->placeholder(__('app.label.language_all'))
                                    ->helperText(__('app.helper.document_language'))
                                    ->native(false),

                                Fields::file('documents')
                                    ->required()
                                    ->helperText(__('app.helper.document_file')),
                            ])
                            ->itemLabel(fn (array $state): string => blank($state['language'] ?? null)
                                ? __('app.label.language_all')
                                : __("app.label.{$state['language']}"))
                            ->collapsible()
                            ->orderColumn('sort')
                            ->minItems(1)
                            ->defaultItems(1)
                            ->addActionLabel(__('app.action.add_file'))
                            ->columnSpanFull(),

                        Fields::sort(),

                        Fields::status(),
                    ]),
            ]);
    }
}
