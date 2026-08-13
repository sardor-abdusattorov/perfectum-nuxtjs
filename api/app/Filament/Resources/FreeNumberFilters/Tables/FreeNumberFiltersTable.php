<?php

namespace App\Filament\Resources\FreeNumberFilters\Tables;

use App\Filament\Support\Tables;
use App\Models\FreeNumberFilter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FreeNumberFiltersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('value')
            ->columns([
                TextColumn::make('name')
                    ->label(__('app.label.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('value')
                    ->label(__('app.label.value'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label(__('app.label.filter_type'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => FreeNumberFilter::getTypeOptions()[$state] ?? $state),

                Tables::statusColumn(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label(__('app.label.filter_type'))
                    ->options(FreeNumberFilter::getTypeOptions()),

                Tables::statusFilter(),
            ])
            ->recordActions(Tables::actions())
            ->toolbarActions(Tables::bulkActions());
    }
}
