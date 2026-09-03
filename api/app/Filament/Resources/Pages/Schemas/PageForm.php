<?php

namespace App\Filament\Resources\Pages\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Support\Fields;
use App\Models\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
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
                        Toggle::make('is_group')
                            ->label(__('app.label.is_group'))
                            ->helperText(__('app.helper.is_group'))
                            ->live(),

                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('title')
                                    ->label(__('app.label.title'))
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(Fields::slugPreview()),

                                Fields::editor('content')
                                    ->label(__('app.label.content'))
                                    ->helperText(__('app.helper.page_content'))
                                    ->visible(fn (Get $get): bool => ! $get('is_group')),
                            ]),

                        Fields::slug()
                            ->helperText(__('app.helper.page_slug')),

                        TagsInput::make('redirect_from')
                            ->label(__('app.label.redirect_from'))
                            ->helperText(__('app.helper.redirect_from'))
                            ->placeholder('static-pages/oferta')
                            ->visible(fn (Get $get): bool => ! $get('is_group')),

                        Fields::image('pages')
                            ->label(__('app.label.image'))
                            ->visible(fn (Get $get): bool => ! $get('is_group')),

                        Fields::sort(),

                        Fields::status(),
                    ]),

                /**
                 * A group holds nothing but the pages it gathers, so the text,
                 * the picture and the SEO block go away with the switch — what
                 * is left is a heading, an address and the cards themselves.
                 */
                Section::make(__('app.label.page_cards'))
                    ->description(__('app.helper.page_cards'))
                    ->visible(fn (Get $get): bool => (bool) $get('is_group'))
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
                    ->visible(fn (Get $get): bool => ! $get('is_group'))
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
