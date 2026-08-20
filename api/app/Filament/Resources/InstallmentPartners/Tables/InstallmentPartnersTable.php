<?php

namespace App\Filament\Resources\InstallmentPartners\Tables;

use App\Filament\Support\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InstallmentPartnersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->columns([
                ImageColumn::make('logo')
                    ->label(__('app.label.logo'))
                    ->disk('public'),

                TextColumn::make('name')
                    ->label(__('app.label.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('installments_count')
                    ->label(__('app.label.device_plural'))
                    ->counts('installments'),

                TextColumn::make('sort')
                    ->label(__('app.label.sort'))
                    ->sortable(),

                Tables::statusColumn(),
            ])
            ->filters([
                Tables::statusFilter(),
            ])
            ->recordActions(Tables::actions())
            ->toolbarActions(Tables::bulkActions());
    }
}
