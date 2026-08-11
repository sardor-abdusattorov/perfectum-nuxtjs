<?php

namespace App\Filament\Resources\News\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\CategoryType;
use App\Filament\Support\CategorySelect;
use App\Filament\Support\ImageUpload;
use App\Filament\Support\SlugInput;
use App\Filament\Support\StatusToggle;
use App\Filament\Support\TextEditor;
use App\Filament\Support\Translated;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.basic_information'))
                    ->schema([
                        CategorySelect::make(CategoryType::News),

                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('title')
                                    ->label(__('app.label.title'))
                                    ->required(Translated::required())
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state, string $operation) => $operation === 'create'
                                        ? $set('slug', Str::slug($state ?? ''))
                                        : null),

                                Textarea::make('excerpt')
                                    ->label(__('app.label.excerpt'))
                                    ->helperText(__('app.helper.excerpt'))
                                    ->rows(3),

                                TextEditor::make('content')
                                    ->label(__('app.label.content'))
                                    ->required(Translated::required()),
                            ]),

                        SlugInput::make(),

                        ImageUpload::make('news'),

                        DateTimePicker::make('published_at')
                            ->label(__('app.label.published_at'))
                            ->helperText(__('app.helper.published_at'))
                            ->seconds(false)
                            ->default(now()),

                        Toggle::make('is_featured')
                            ->label(__('app.label.is_featured'))
                            ->helperText(__('app.helper.is_featured')),

                        StatusToggle::make(),
                    ]),
            ]);
    }
}
