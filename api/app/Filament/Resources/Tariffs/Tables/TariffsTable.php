<?php

namespace App\Filament\Resources\Tariffs\Tables;

use App\Filament\Support\Tables;
use App\Models\TariffCategory;
use App\Models\TariffType;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TariffsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->reorderable('sort')
            ->columns([
                TextColumn::make('name')
                    ->label(__('app.label.name'))
                    ->searchable()
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label(__('app.label.category'))
                    ->badge()
                    ->placeholder('—'),

                TextColumn::make('type.name')
                    ->label(__('app.label.tariff_type'))
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),

                TextColumn::make('price')
                    ->label(__('app.label.price_value'))
                    ->description(fn ($record): ?string => $record->price_period)
                    ->placeholder('—'),

                Tables::viewsColumn(),

                Tables::statusColumn(),
            ])
            ->filters([
                Tables::categoryFilter(TariffCategory::class),

                Tables::categoryFilter(TariffType::class, 'type_id')
                    ->label(__('app.label.tariff_type')),

                Tables::statusFilter(),
            ])
            ->recordActions(Tables::actions())
            ->toolbarActions(Tables::bulkActions());
    }
}
