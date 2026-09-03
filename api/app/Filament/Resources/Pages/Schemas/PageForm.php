<?php

namespace App\Filament\Resources\Pages\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Support\Fields;
use App\Models\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

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
                                    ->afterStateUpdated(Fields::slugPreview()),

                                Fields::editor('content')
                                    ->label(__('app.label.content'))
                                    ->helperText(__('app.helper.page_content')),
                            ]),

                        Fields::slug()
                            ->helperText(__('app.helper.page_slug')),

                        TagsInput::make('redirect_from')
                            ->label(__('app.label.redirect_from'))
                            ->helperText(__('app.helper.redirect_from'))
                            ->placeholder('static-pages/oferta'),

                        Fields::image('pages')
                            ->label(__('app.label.image')),

                        Fields::sort(),

                        Fields::status(),
                    ]),

                Section::make(__('app.label.page_cards'))
                    ->description(__('app.helper.page_cards'))
                    ->collapsed(fn (?Page $record): bool => $record?->children()->doesntExist() ?? true)
                    ->schema([
                        Select::make('children')
                            ->hiddenLabel()
                            ->relationship('children', 'title', ignoreRecord: true)
                            ->getOptionLabelFromRecordUsing(fn (Page $record): string => $record->title)
                            ->multiple()
                            ->searchable()
                            ->preload(),
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
                            ]),
                    ]),
            ]);
    }
}
