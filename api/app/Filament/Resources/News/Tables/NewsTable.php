<?php

namespace App\Filament\Resources\News\Tables;

use App\Filament\Support\Tables;
use App\Models\NewsCategory;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
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

                TextColumn::make('network')
                    ->label(__('app.label.network'))
                    ->badge(),

                TextColumn::make('category.name')
                    ->label(__('app.label.category'))
                    ->badge()
                    ->placeholder('—'),

                TextColumn::make('published_at')
                    ->label(__('app.label.published_at'))
                    ->date()
                    ->sortable(),

                Tables::statusColumn(),
            ])
            ->filters([
                Tables::categoryFilter(NewsCategory::class),

                Tables::statusFilter(),
            ])
            ->recordActions(Tables::actions())
            ->toolbarActions(Tables::bulkActions());
    }
}
