<?php

namespace App\Filament\Resources\Categories\Tables;

use App\Enums\CategoryType;
use App\Enums\Network;
use App\Filament\Support\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CategoriesTable
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

                TextColumn::make('type')
                    ->label(__('app.label.category_type'))
                    ->badge()
                    ->formatStateUsing(fn (CategoryType $state): string => $state->getLabel()),

                TextColumn::make('network')
                    ->label(__('app.label.network'))
                    ->badge()
                    ->formatStateUsing(fn (Network $state): string => $state->getLabel())
                    ->color(fn (Network $state): string => match ($state) {
                        Network::FiveG => 'danger',
                        Network::Cdma => 'warning',
                        Network::Both => 'gray',
                    }),

                TextColumn::make('slug')
                    ->label(__('app.label.key'))
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                Tables::statusColumn(),

                TextColumn::make('sort')
                    ->label(__('app.label.sort'))
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label(__('app.label.category_type'))
                    ->options(CategoryType::getOptions()),

                SelectFilter::make('network')
                    ->label(__('app.label.network'))
                    ->options(Network::getOptions()),

                Tables::statusFilter(),
            ])
            ->recordActions(Tables::actions())
            ->toolbarActions(Tables::bulkActions());
    }
}
