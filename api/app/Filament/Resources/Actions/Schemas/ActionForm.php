<?php

namespace App\Filament\Resources\Actions\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\CategoryType;
use App\Filament\Support\CategorySelect;
use App\Filament\Support\ImageUpload;
use App\Filament\Support\SlugInput;
use App\Filament\Support\StatusToggle;
use App\Filament\Support\TextEditor;
use App\Filament\Support\Translated;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ActionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.basic_information'))
                    ->schema([
                        CategorySelect::make(CategoryType::Action),

                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('title')
                                    ->label(__('app.label.title'))
                                    ->required(Translated::required())
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, Get $get, ?string $state, string $operation) => $operation === 'create' && blank($get('slug'))
                                        ? $set('slug', Str::slug($state ?? ''))
                                        : null),

                                TextInput::make('badge')
                                    ->label(__('app.label.badge'))
                                    ->helperText(__('app.helper.badge')),

                                Textarea::make('excerpt')
                                    ->label(__('app.label.excerpt'))
                                    ->helperText(__('app.helper.excerpt'))
                                    ->rows(3),

                                TextEditor::make('content')
                                    ->label(__('app.label.content'))
                                    ->required(Translated::required()),
                            ]),

                        SlugInput::make(),

                        ImageUpload::make('actions'),

                        DatePicker::make('starts_at')
                            ->label(__('app.label.starts_at')),

                        DatePicker::make('ends_at')
                            ->label(__('app.label.ends_at'))
                            ->helperText(__('app.helper.ends_at'))
                            ->afterOrEqual('starts_at'),

                        StatusToggle::make(),
                    ]),
            ]);
    }
}
