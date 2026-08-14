<?php

namespace App\Filament\Resources\ApplicationThemes\Tables;

use App\Filament\Support\Tables;
use App\Models\ApplicationTheme;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ApplicationThemesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->withCount('applications'))
            ->columns([
                TextColumn::make('name')
                    ->label(__('app.label.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('applications_count')
                    ->label(__('app.label.application_plural'))
                    ->badge()
                    ->sortable(),

                TextColumn::make('sort')
                    ->label(__('app.label.sort'))
                    ->sortable(),

                Tables::statusColumn(),
            ])
            ->filters([
                Tables::statusFilter(),
            ])
            ->recordActions([
                ViewAction::make(),

                EditAction::make(),

                DeleteAction::make()
                    ->authorize(fn (ApplicationTheme $record): bool => ! $record->isInUse())
                    ->authorizationTooltip()
                    ->authorizationMessage(__('app.helper.theme_in_use')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorizeIndividualRecords(fn (ApplicationTheme $record): bool => ! $record->isInUse())
                        ->authorizationMessage(__('app.helper.theme_in_use')),
                ]),
            ]);
    }
}
