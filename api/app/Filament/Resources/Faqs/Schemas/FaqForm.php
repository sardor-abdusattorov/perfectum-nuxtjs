<?php

namespace App\Filament\Resources\Faqs\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Support\Fields;
use App\Models\Faq;
use App\Models\FaqCategory;
use Filament\Forms\Components\Select;
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
                        Fields::category(FaqCategory::class),

                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('question')
                                    ->label(__('app.label.question'))
                                    ->required(),

                                Fields::editor('answer')
                                    ->label(__('app.label.answer'))
                                    ->required(),
                            ]),

                        Select::make('pages')
                            ->label(__('app.label.faq_pages'))
                            ->helperText(__('app.helper.faq_pages'))
                            ->options(Faq::getPageOptions())
                            ->multiple()
                            ->required()
                            ->default([Faq::PAGE_FAQ]),

                        Fields::sort(),

                        Fields::status(),
                    ]),
            ]);
    }
}
