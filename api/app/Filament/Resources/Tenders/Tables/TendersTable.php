<?php

namespace App\Filament\Resources\Tenders\Tables;

use App\Enums\TenderState;
use App\Filament\Support\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TendersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('deadline_at', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->label(__('app.label.title'))
                    ->searchable()
                    ->wrap()
                    ->sortable(),

                TextColumn::make('state')
                    ->label(__('app.label.tender_state'))
                    ->badge()
                    ->formatStateUsing(fn (TenderState $state): string => $state->getLabel())
                    ->color(fn (TenderState $state): string => $state === TenderState::Open ? 'success' : 'gray'),

                TextColumn::make('deadline_at')
                    ->label(__('app.label.deadline_at'))
                    ->date()
                    ->placeholder('—')
                    ->sortable(),

                Tables::viewsColumn(),

                Tables::statusColumn(),
            ])
            ->filters([
                SelectFilter::make('state')
                    ->label(__('app.label.tender_state'))
                    ->options(TenderState::getOptions()),

                Tables::statusFilter(),
            ])
            ->recordActions(Tables::actions())
            ->toolbarActions(Tables::bulkActions());
    }
}
