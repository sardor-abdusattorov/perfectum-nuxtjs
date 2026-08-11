<?php

namespace App\Filament\Resources\Categories\Tables;

use App\Enums\CategoryType;
use App\Enums\Network;
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

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->columns([
                TextColumn::make('name')
                    ->label(__('app.label.name'))
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('type')
                    ->label(__('app.label.category_type'))
                    ->badge()
                    ->formatStateUsing(fn (CategoryType $state): string => $state->getLabel()),

                TextColumn::make('network')
                    ->label(__('app.label.network'))
                    ->badge()
                    ->formatStateUsing(fn (Network $state): string => $state->getLabel())
                    ->color(fn (Network $state): string => match ($state) {
                        Network::FiveG => 'danger',
                        Network::Cdma => 'warning',
                        Network::Both => 'gray',
                    }),

                TextColumn::make('slug')
                    ->label(__('app.label.key'))
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                ToggleColumn::make('status')
                    ->label(__('app.label.show_on_site'))
                    ->sortable()
                    ->onIcon('heroicon-m-check-circle')
                    ->offIcon('heroicon-m-x-circle')
                    ->onColor('success')
                    ->offColor('danger'),

                TextColumn::make('sort')
                    ->label(__('app.label.sort'))
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label(__('app.label.category_type'))
                    ->options(CategoryType::getOptions()),

                SelectFilter::make('network')
                    ->label(__('app.label.network'))
                    ->options(Network::getOptions()),

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
