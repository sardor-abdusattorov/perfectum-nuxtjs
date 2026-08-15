<?php

namespace App\Filament\Resources\TariffFiles\Tables;

use App\Filament\Support\Tables;
use App\Models\TariffFile;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TariffFilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->columns([
                TextColumn::make('name')
                    ->label(__('app.label.name'))
                    ->searchable()
                    ->wrap(),

                IconColumn::make('file')
                    ->label(__('app.label.file'))
                    ->state(fn (TariffFile $record): bool => filled(stored_url($record->file)))
                    ->boolean()
                    ->tooltip(fn (TariffFile $record): string => filled(stored_url($record->file))
                        ? __('app.helper.file_attached')
                        : __('app.helper.file_missing')),

                TextColumn::make('created_at')
                    ->label(__('app.label.created_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

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
