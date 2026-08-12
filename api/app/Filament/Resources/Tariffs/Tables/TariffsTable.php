<?php

namespace App\Filament\Resources\Tariffs\Tables;

use App\Enums\CategoryType;
use App\Filament\Support\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
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

                TextColumn::make('ussd')
                    ->label(__('app.label.ussd'))
                    ->badge()
                    ->color('gray')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables::statusColumn(),
            ])
            ->filters([
                Tables::categoryFilter(CategoryType::Tariff),

                Tables::categoryFilter(CategoryType::TariffType, 'type_id')
                    ->label(__('app.label.tariff_type')),

                SelectFilter::make('is_archived')
                    ->label(__('app.label.is_archived'))
                    ->options([
                        0 => __('app.label.no'),
                        1 => __('app.label.yes'),
                    ]),

                Tables::statusFilter(),
            ])
            ->recordActions(Tables::actions())
            ->toolbarActions(Tables::bulkActions());
    }
}
