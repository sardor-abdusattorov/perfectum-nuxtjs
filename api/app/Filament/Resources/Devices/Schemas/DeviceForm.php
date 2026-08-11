<?php

namespace App\Filament\Resources\Devices\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\CategoryType;
use App\Filament\Support\CategorySelect;
use App\Filament\Support\ImageUpload;
use App\Filament\Support\SlugInput;
use App\Filament\Support\StatusToggle;
use App\Filament\Support\TextEditor;
use App\Filament\Support\Translated;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class DeviceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.basic_information'))
                    ->schema([
                        CategorySelect::make(CategoryType::Device),

                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('app.label.name'))
                                    ->required(Translated::required())
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state, string $operation) => $operation === 'create'
                                        ? $set('slug', Str::slug($state ?? ''))
                                        : null),

                                Textarea::make('excerpt')
                                    ->label(__('app.label.excerpt'))
                                    ->rows(3),

                                TextEditor::make('content')
                                    ->label(__('app.label.content')),
                            ]),

                        SlugInput::make(),

                        TextInput::make('brand')
                            ->label(__('app.label.brand')),

                        ImageUpload::make('devices'),

                        TextInput::make('price')
                            ->label(__('app.label.price'))
                            ->helperText(__('app.helper.price'))
                            ->numeric()
                            ->minValue(0),

                        Toggle::make('in_stock')
                            ->label(__('app.label.in_stock'))
                            ->default(true),

                        TextInput::make('sort')
                            ->label(__('app.label.sort'))
                            ->helperText(__('app.helper.sort'))
                            ->numeric()
                            ->default(0)
                            ->required(),

                        StatusToggle::make(),
                    ]),

                Section::make(__('app.label.specs'))
                    ->description(__('app.helper.specs'))
                    ->schema([
                        Repeater::make('specs')
                            ->hiddenLabel()
                            ->addActionLabel(__('app.action.add'))
                            ->schema([
                                TranslatableTabs::make('spec_translations')
                                    ->schema([
                                        TextInput::make('label')
                                            ->label(__('app.label.name')),

                                        TextInput::make('value')
                                            ->label(__('app.label.value')),
                                    ]),
                            ])
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),
                    ]),
            ]);
    }
}
