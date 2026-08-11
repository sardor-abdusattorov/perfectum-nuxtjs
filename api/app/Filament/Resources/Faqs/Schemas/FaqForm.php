<?php

namespace App\Filament\Resources\Faqs\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\CategoryType;
use App\Filament\Support\CategorySelect;
use App\Filament\Support\SortInput;
use App\Filament\Support\StatusToggle;
use App\Filament\Support\TextEditor;
use App\Filament\Support\Translated;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.basic_information'))
                    ->schema([
                        CategorySelect::make(CategoryType::Faq),

                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('question')
                                    ->label(__('app.label.question'))
                                    ->required(Translated::required()),

                                TextEditor::make('answer')
                                    ->label(__('app.label.answer'))
                                    ->required(Translated::required()),
                            ]),

                        SortInput::make(),

                        StatusToggle::make(),
                    ]),
            ]);
    }
}
