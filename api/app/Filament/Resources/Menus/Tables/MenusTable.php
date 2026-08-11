<?php

namespace App\Filament\Resources\Menus\Tables;

use App\Enums\MenuLocation;
use App\Filament\Support\CrudActions;
use App\Filament\Support\StatusColumn;
use App\Filament\Support\StatusFilter;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MenusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->columns([
                TextColumn::make('name')
                    ->label(__('app.label.name'))
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('parent.name')
                    ->label(__('app.label.parent_item'))
                    ->placeholder('—')
                    ->sortable(),

                TextColumn::make('location')
                    ->label(__('app.label.menu_location'))
                    ->badge()
                    ->formatStateUsing(fn (MenuLocation $state): string => $state->getLabel())
                    ->color(fn (MenuLocation $state): string => match ($state) {
                        MenuLocation::Header => 'primary',
                        MenuLocation::Footer => 'gray',
                    }),

                TextColumn::make('key')
                    ->label(__('app.label.key'))
                    ->badge()
                    ->color('gray')
                    ->placeholder('—')
                    ->searchable(),

                TextColumn::make('url')
                    ->label(__('app.label.url'))
                    ->placeholder('—')
                    ->wrap(),

                IconColumn::make('open_in_new_tab')
                    ->label(__('app.label.open_in_new_tab'))
                    ->boolean(),

                StatusColumn::make(),

                TextColumn::make('sort')
                    ->label(__('app.label.sort'))
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('location')
                    ->label(__('app.label.menu_location'))
                    ->options(MenuLocation::getLocationOptions()),

                StatusFilter::make(),
            ])
            ->recordActions(CrudActions::record())
            ->toolbarActions(CrudActions::bulk());
    }
}
