<?php

namespace App\Filament\Resources\TariffCategories\Tables;

use App\Filament\Support\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TariffCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->columns([
                TextColumn::make('name')
                    ->label(__('app.label.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('network')
                    ->label(__('app.label.network'))
                    ->badge(),

                TextColumn::make('sort')
                    ->label(__('app.label.sort'))
                    ->sortable(),

                Tables::statusColumn(),
            ])
            ->filters([
                Tables::statusFilter(),
            ])
            ->recordActions(Tables::actions())
            ->toolbarActions(Tables::bulkActions());
    }
}
