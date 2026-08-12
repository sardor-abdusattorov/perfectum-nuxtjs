<?php

namespace App\Filament\Resources\Actions\Tables;

use App\Filament\Support\Tables;
use App\Models\ActionCategory;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('ends_at', 'desc')
            ->columns([
                ImageColumn::make('image')
                    ->label(__('app.label.image'))
                    ->disk('public')
                    ->square(),

                TextColumn::make('title')
                    ->label(__('app.label.title'))
                    ->searchable()
                    ->wrap()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label(__('app.label.category'))
                    ->badge()
                    ->placeholder('—'),

                TextColumn::make('ends_at')
                    ->label(__('app.label.ends_at'))
                    ->date()
                    ->placeholder('—')
                    ->sortable(),

                Tables::statusColumn(),
            ])
            ->filters([
                Tables::categoryFilter(ActionCategory::class),

                Tables::statusFilter(),
            ])
            ->recordActions(Tables::actions())
            ->toolbarActions(Tables::bulkActions());
    }
}
