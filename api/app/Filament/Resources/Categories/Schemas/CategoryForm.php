<?php

namespace App\Filament\Resources\Categories\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\CategoryType;
use App\Enums\Network;
use App\Filament\Support\SlugInput;
use App\Filament\Support\StatusToggle;
use App\Filament\Support\Translated;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

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
                                    ->required(Translated::required())
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, Get $get, ?string $state, string $operation) => $operation === 'create' && blank($get('slug'))
                                        ? $set('slug', Str::slug($state ?? ''))
                                        : null),
                            ]),

                        SlugInput::make()
                            ->label(__('app.label.key'))
                            ->helperText(__('app.helper.category_slug'))
                            ->unique(ignoreRecord: true, modifyRuleUsing: fn (Unique $rule, Get $get): Unique => $rule->where('type', $get('type'))),

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
