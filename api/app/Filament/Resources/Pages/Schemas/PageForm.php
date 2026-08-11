<?php

namespace App\Filament\Resources\Pages\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Support\ImageUpload;
use App\Filament\Support\TextEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
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
                                    ->afterStateUpdated(fn (Set $set, ?string $state, string $operation) => $operation === 'create'
                                        ? $set('slug', Str::slug($state ?? ''))
                                        : null),

                                TextEditor::make('content')
                                    ->label(__('app.label.content'))
                                    ->required(),
                            ]),

                        TextInput::make('slug')
                            ->label(__('app.label.slug'))
                            ->helperText(__('app.helper.page_slug'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->alphaDash(),

                        ImageUpload::make('pages')
                            ->label(__('app.label.image')),

                        Toggle::make('status')
                            ->label(__('app.label.show_on_site'))
                            ->helperText(__('app.helper.if_disabled_not_shown'))
                            ->default(true),
                    ]),

                Section::make(__('app.label.tab_seo'))
                    ->description(__('app.helper.page_seo'))
                    ->collapsed()
                    ->schema([
                        TranslatableTabs::make('seo_translations')
                            ->schema([
                                TextInput::make('meta_title')
                                    ->label(__('app.label.seo_title')),

                                Textarea::make('meta_description')
                                    ->label(__('app.label.seo_description'))
                                    ->rows(3),

                                Textarea::make('meta_keywords')
                                    ->label(__('app.label.seo_keywords'))
                                    ->rows(2),
                            ]),
                    ]),
            ]);
    }
}
