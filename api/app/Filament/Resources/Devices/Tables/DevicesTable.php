<?php

namespace App\Filament\Resources\Devices\Tables;

use App\Filament\Support\Tables;
use App\Models\DeviceCategory;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DevicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->columns([
                ImageColumn::make('image')
                    ->label(__('app.label.image'))
                    ->disk('public')
                    ->square(),

                TextColumn::make('name')
                    ->label(__('app.label.name'))
                    ->searchable()
                    ->wrap()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label(__('app.label.category'))
                    ->badge()
                    ->placeholder('—'),

                TextColumn::make('brand')
                    ->label(__('app.label.brand'))
                    ->placeholder('—')
                    ->sortable(),

                TextColumn::make('price')
                    ->label(__('app.label.price'))
                    ->numeric()
                    ->placeholder('—')
                    ->sortable(),

                IconColumn::make('in_stock')
                    ->label(__('app.label.in_stock'))
                    ->boolean(),

                Tables::statusColumn(),
            ])
            ->filters([
                Tables::categoryFilter(DeviceCategory::class),

                Tables::statusFilter(),
            ])
            ->recordActions(Tables::actions())
            ->toolbarActions(Tables::bulkActions());
    }
}
