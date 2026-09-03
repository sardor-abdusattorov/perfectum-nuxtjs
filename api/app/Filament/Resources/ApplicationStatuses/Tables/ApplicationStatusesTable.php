<?php

namespace App\Filament\Resources\ApplicationStatuses\Tables;

use App\Filament\Support\Tables;
use App\Models\ApplicationStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ApplicationStatusesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->withCount('applications'))
            ->columns([
                TextColumn::make('name')
                    ->label(__('app.label.name'))
                    ->badge()
                    ->color(fn (ApplicationStatus $record): string => $record->color)
                    ->searchable()
                    ->sortable(),

                IconColumn::make('is_default')
                    ->label(__('app.label.is_default_status'))
                    ->boolean(),

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
                    ->authorize(fn (ApplicationStatus $record): bool => ! $record->isInUse())
                    ->authorizationTooltip()
                    ->authorizationMessage(__('app.helper.status_in_use')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorizeIndividualRecords(fn (ApplicationStatus $record): bool => ! $record->isInUse())
                        ->authorizationMessage(__('app.helper.status_in_use')),
                ]),
            ]);
    }
}
