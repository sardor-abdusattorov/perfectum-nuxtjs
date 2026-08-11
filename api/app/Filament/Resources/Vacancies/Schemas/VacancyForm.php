<?php

namespace App\Filament\Resources\Vacancies\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Support\SlugInput;
use App\Filament\Support\StatusToggle;
use App\Filament\Support\TextEditor;
use App\Filament\Support\Translated;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

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
                                    ->required(Translated::required())
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state, string $operation) => $operation === 'create'
                                        ? $set('slug', Str::slug($state ?? ''))
                                        : null),

                                TextInput::make('city')
                                    ->label(__('app.label.city')),

                                TextInput::make('employment')
                                    ->label(__('app.label.employment'))
                                    ->helperText(__('app.helper.employment')),

                                TextInput::make('salary')
                                    ->label(__('app.label.salary')),

                                TextEditor::make('content')
                                    ->label(__('app.label.content'))
                                    ->helperText(__('app.helper.vacancy_content'))
                                    ->required(Translated::required()),
                            ]),

                        SlugInput::make(),

                        TextInput::make('sort')
                            ->label(__('app.label.sort'))
                            ->helperText(__('app.helper.sort'))
                            ->numeric()
                            ->default(0)
                            ->required(),

                        StatusToggle::make(),
                    ]),
            ]);
    }
}
