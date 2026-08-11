<?php

namespace App\Filament\Resources\Tariffs\Tables;

use App\Enums\PublishedStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TariffsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->reorderable('sort')
            ->columns([
                TextColumn::make('name')
                    ->label(__('app.label.name'))
                    ->searchable()
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label(__('app.label.category'))
                    ->badge()
                    ->placeholder('—'),

                TextColumn::make('type.name')
                    ->label(__('app.label.tariff_type'))
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),

                TextColumn::make('price')
                    ->label(__('app.label.price_value'))
                    ->description(fn ($record): ?string => $record->price_period)
                    ->placeholder('—'),

                TextColumn::make('ussd')
                    ->label(__('app.label.ussd'))
                    ->badge()
                    ->color('gray')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                ToggleColumn::make('status')
                    ->label(__('app.label.show_on_site'))
                    ->sortable()
                    ->onIcon('heroicon-m-check-circle')
                    ->offIcon('heroicon-m-x-circle')
                    ->onColor('success')
                    ->offColor('danger'),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label(__('app.label.category'))
                    ->relationship('category', 'slug'),

                SelectFilter::make('type')
                    ->label(__('app.label.tariff_type'))
                    ->relationship('type', 'slug'),

                SelectFilter::make('is_archived')
                    ->label(__('app.label.is_archived'))
                    ->options([
                        0 => __('app.label.no'),
                        1 => __('app.label.yes'),
                    ]),

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
