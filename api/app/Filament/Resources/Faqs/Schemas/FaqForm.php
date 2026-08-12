<?php

namespace App\Filament\Resources\Faqs\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Support\Fields;
use App\Models\FaqCategory;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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

                        Toggle::make('is_featured')
                            ->label(__('app.label.faq_featured'))
                            ->helperText(__('app.helper.faq_featured')),

                        Fields::sort(),

                        Fields::status(),
                    ]),
            ]);
    }
}
