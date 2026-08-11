<?php

namespace App\Filament\Resources\Tenders\Tables;

use App\Enums\PublishedStatus;
use App\Enums\TenderState;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
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


                ToggleColumn::make('status')
                    ->label(__('app.label.show_on_site'))
                    ->sortable()
                    ->onIcon('heroicon-m-check-circle')
                    ->offIcon('heroicon-m-x-circle')
                    ->onColor('success')
                    ->offColor('danger'),
            ])
            ->filters([
                SelectFilter::make('state')
                    ->label(__('app.label.tender_state'))
                    ->options(TenderState::getOptions()),


                SelectFilter::make('status')
                    ->label(__('app.label.status'))
                    ->options(PublishedStatus::getStatusOptions()),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
