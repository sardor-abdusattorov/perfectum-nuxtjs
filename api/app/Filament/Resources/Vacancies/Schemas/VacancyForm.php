<?php

namespace App\Filament\Resources\Vacancies\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Support\Fields;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VacancyForm
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

                                TextInput::make('department')
                                    ->label(__('app.label.department')),

                                TextInput::make('city')
                                    ->label(__('app.label.city')),

                                TextInput::make('employment')
                                    ->label(__('app.label.employment'))
                                    ->helperText(__('app.helper.employment')),

                                TextInput::make('salary')
                                    ->label(__('app.label.salary')),

                                Fields::editor('content')
                                    ->label(__('app.label.content'))
                                    ->helperText(__('app.helper.vacancy_content'))
                                    ->required(),
                            ]),

                        Fields::slug(),

                        Fields::sort(),

                        Fields::status(),
                    ]),
            ]);
    }
}
