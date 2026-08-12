<?php

namespace App\Filament\Resources\Offices\Tables;

use App\Enums\OfficeType;
use App\Filament\Support\Tables;
use App\Models\Region;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OfficesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->columns([
                TextColumn::make('type')
                    ->label(__('app.label.office_type'))
                    ->badge(),

                TextColumn::make('name')
                    ->label(__('app.label.office_name'))
                    ->searchable()
                    ->placeholder('—')
                    ->sortable(),

                TextColumn::make('region.name')
                    ->label(__('app.label.region_single'))
                    ->badge()
                    ->placeholder('—'),

                TextColumn::make('district')
                    ->label(__('app.label.district'))
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('address')
                    ->label(__('app.label.address'))
                    ->searchable()
                    ->wrap(),

                TextColumn::make('phone')
                    ->label(__('app.label.phone'))
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('network')
                    ->label(__('app.label.network'))
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables::statusColumn(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label(__('app.label.office_type'))
                    ->options(OfficeType::getOptions()),

                Tables::categoryFilter(Region::class, 'region_id')
                    ->label(__('app.label.region_single')),

                Tables::statusFilter(),
            ])
            ->recordActions(Tables::actions())
            ->toolbarActions(Tables::bulkActions());
    }
}
