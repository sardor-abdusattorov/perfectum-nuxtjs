<?php

namespace App\Filament\Resources\Services\Tables;

use App\Filament\Support\Tables;
use App\Models\ServiceCategory;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->reorderable('sort')
            ->columns([
                ImageColumn::make('icon')
                    ->label(__('app.label.icon'))
                    ->disk('public')
                    ->square(),

                TextColumn::make('name')
                    ->label(__('app.label.name'))
                    ->searchable()
                    ->weight('bold')
                    ->wrap()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label(__('app.label.category'))
                    ->badge()
                    ->placeholder('—'),

                TextColumn::make('ussd')
                    ->label(__('app.label.ussd'))
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),

                Tables::viewsColumn(),

                Tables::statusColumn(),
            ])
            ->filters([
                Tables::categoryFilter(ServiceCategory::class),

                Tables::statusFilter(),
            ])
            ->recordActions(Tables::actions())
            ->toolbarActions(Tables::bulkActions());
    }
}
