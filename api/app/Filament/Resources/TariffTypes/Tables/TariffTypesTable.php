<?php

namespace App\Filament\Resources\TariffTypes\Tables;

use App\Filament\Support\Tables;
use App\Models\TariffCategory;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TariffTypesTable
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

                TextColumn::make('category.name')
                    ->label(__('app.label.tariff_categories_single'))
                    ->badge()
                    ->placeholder('—'),

                TextColumn::make('slug')
                    ->label(__('app.label.slug'))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('sort')
                    ->label(__('app.label.sort'))
                    ->sortable(),

                Tables::statusColumn(),
            ])
            ->filters([
                Tables::categoryFilter(TariffCategory::class)
                    ->label(__('app.label.tariff_categories_single')),

                Tables::statusFilter(),
            ])
            ->recordActions(Tables::actions())
            ->toolbarActions(Tables::bulkActions());
    }
}
