<?php

namespace App\Filament\Resources\Pages\Tables;

use App\Filament\Support\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('image')
                    ->label(__('app.label.image'))
                    ->disk('public')
                    ->square(),

                TextColumn::make('title')
                    ->label(__('app.label.title'))
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('slug')
                    ->label(__('app.label.slug'))
                    ->badge()
                    ->color('gray')
                    ->copyable()
                    ->searchable(),

                TextColumn::make('children_count')
                    ->label(__('app.label.page_cards'))
                    ->counts('children')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn (int $state): ?string => $state > 0 ? (string) $state : null)
                    ->placeholder('—'),

                Tables::viewsColumn(),

                Tables::statusColumn(),

                TextColumn::make('updated_at')
                    ->label(__('app.label.updated_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables::statusFilter(),
            ])
            ->recordActions(Tables::actions())
            ->toolbarActions(Tables::bulkActions());
    }
}
