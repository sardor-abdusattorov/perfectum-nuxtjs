<?php

namespace App\Filament\Resources\Categories\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\CategoryType;
use App\Enums\Network;
use App\Filament\Support\StatusToggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.basic_information'))
                    ->schema([
                        Select::make('type')
                            ->label(__('app.label.category_type'))
                            ->helperText(__('app.helper.category_type'))
                            ->options(CategoryType::getOptions())
                            ->required(),

                        Select::make('network')
                            ->label(__('app.label.network'))
                            ->helperText(__('app.helper.network'))
                            ->options(Network::getOptions())
                            ->default(Network::Both->value)
                            ->required(),

                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('app.label.name'))
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state, string $operation) => $operation === 'create'
                                        ? $set('slug', Str::slug($state ?? ''))
                                        : null),
                            ]),

                        TextInput::make('slug')
                            ->label(__('app.label.key'))
                            ->helperText(__('app.helper.category_slug'))
                            ->required()
                            ->alphaDash(),

                        TextInput::make('sort')
                            ->label(__('app.label.sort'))
                            ->helperText(__('app.helper.sort'))
                            ->numeric()
                            ->default(0)
                            ->required(),

                        StatusToggle::make(),
                    ]),
            ]);
    }
}
