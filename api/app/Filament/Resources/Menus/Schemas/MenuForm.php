<?php

namespace App\Filament\Resources\Menus\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\MenuLocation;
use App\Models\Menu;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.basic_information'))
                    ->description(__('app.label.menu_structure'))
                    ->schema([
                        Select::make('location')
                            ->label(__('app.label.menu_location'))
                            ->options(MenuLocation::getLocationOptions())
                            ->default(MenuLocation::Header->value)
                            ->required()
                            ->live(),

                        Select::make('parent_id')
                            ->label(__('app.label.parent_item'))
                            ->helperText(__('app.helper.leave_empty_for_top_level'))
                            ->options(fn (Get $get, ?Menu $record): array => static::parentOptions($get('location'), $record))
                            ->searchable()
                            ->preload()
                            ->live(),

                        TextInput::make('column_position')
                            ->label(__('app.label.column_position'))
                            ->helperText(__('app.helper.footer_column_position'))
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(4)
                            ->visible(fn (Get $get): bool => $get('location') === MenuLocation::Footer->value
                                && blank($get('parent_id'))),

                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('app.label.name'))
                                    ->required(),

                                TextInput::make('url')
                                    ->label(__('app.label.url'))
                                    ->helperText(__('app.helper.menu_url')),
                            ]),

                        Toggle::make('open_in_new_tab')
                            ->label(__('app.label.open_in_new_tab'))
                            ->default(false),

                        TextInput::make('sort')
                            ->label(__('app.label.sort'))
                            ->helperText(__('app.helper.sort'))
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Toggle::make('status')
                            ->label(__('app.label.show_on_site'))
                            ->helperText(__('app.helper.if_disabled_menu_item_not_shown'))
                            ->default(true),
                    ]),
            ]);
    }

    /**
     * Top level items of the same menu, excluding the record being edited so an
     * item cannot become its own parent.
     *
     * @return array<int, string>
     */
    private static function parentOptions(?string $location, ?Menu $record): array
    {
        return Menu::query()
            ->whereNull('parent_id')
            ->when($location, fn ($query) => $query->where('location', $location))
            ->when($record, fn ($query) => $query->whereKeyNot($record->getKey()))
            ->orderBy('sort')
            ->get()
            ->mapWithKeys(fn (Menu $menu): array => [$menu->getKey() => $menu->name])
            ->all();
    }
}
