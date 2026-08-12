<?php

namespace App\Filament\Resources\Socials\Tables;

use App\Filament\Support\Tables;
use App\Support\IconName;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SocialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->columns([
                IconColumn::make('icon')
                    ->label(__('app.label.icon'))
                    ->icon(fn (?string $state): ?string => IconName::blade($state)),

                TextColumn::make('name')
                    ->label(__('app.label.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('url')
                    ->label(__('app.label.url'))
                    ->url(fn (string $state): string => $state)
                    ->openUrlInNewTab()
                    ->wrap(),

                Tables::statusColumn(),

                TextColumn::make('sort')
                    ->label(__('app.label.sort'))
                    ->sortable(),
            ])
            ->filters([
                Tables::statusFilter(),
            ])
            ->recordActions(Tables::actions())
            ->toolbarActions(Tables::bulkActions());
    }
}
