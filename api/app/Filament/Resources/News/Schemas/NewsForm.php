<?php

namespace App\Filament\Resources\News\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\Network;
use App\Filament\Support\Fields;
use App\Models\NewsCategory;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.basic_information'))
                    ->schema([
                        Fields::network()
                            ->options(collect(Network::getOptions())->except(Network::Both->value)->all())
                            ->default(Network::FiveG->value),

                        Fields::category(NewsCategory::class)
                            ->required(),

                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('title')
                                    ->label(__('app.label.title'))
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(Fields::slugPreview()),

                                Textarea::make('excerpt')
                                    ->label(__('app.label.excerpt'))
                                    ->helperText(__('app.helper.excerpt'))
                                    ->rows(3),

                                Fields::editor('content')
                                    ->label(__('app.label.content'))
                                    ->required(),
                            ]),

                        Fields::slug(),

                        Fields::image('news', 'preview_image')
                            ->label(__('app.label.preview_image'))
                            ->helperText(__('app.helper.preview_image')),

                        Fields::image('news', 'main_image')
                            ->label(__('app.label.main_image'))
                            ->helperText(__('app.helper.main_image')),

                        DateTimePicker::make('published_at')
                            ->label(__('app.label.published_at'))
                            ->helperText(__('app.helper.published_at'))
                            ->seconds(false)
                            ->default(now()),

                        Toggle::make('is_featured')
                            ->label(__('app.label.is_featured'))
                            ->helperText(__('app.helper.is_featured')),

                        Fields::status(),

                        Fields::byLink(),
                    ]),
            ]);
    }
}
