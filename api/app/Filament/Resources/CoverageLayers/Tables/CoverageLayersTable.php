<?php

namespace App\Filament\Resources\CoverageLayers\Tables;

use App\Filament\Support\Tables;
use App\Models\CoverageLayer;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CoverageLayersTable
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

                TextColumn::make('key')
                    ->label(__('app.label.key'))
                    ->badge(),

                ColorColumn::make('color')
                    ->label(__('app.label.color')),

                TextColumn::make('features')
                    ->label(__('app.label.shapes'))
                    ->state(fn (CoverageLayer $record): string => $record->has_shapes
                        ? (string) $record->features
                        : __('app.label.coverage_unread')),

                Tables::statusColumn(),
            ])
            ->filters([
                Tables::statusFilter(),
            ])
            ->recordActions(Tables::actions())
            ->toolbarActions(Tables::bulkActions());
    }
}
