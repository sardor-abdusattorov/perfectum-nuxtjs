<?php

namespace App\Filament\Resources\Applications\Tables;

use App\Filament\Resources\Applications\Actions\ChangeApplicationStatusAction;
use App\Models\Application;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ApplicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('phone')
                    ->label(__('app.label.phone'))
                    ->searchable(),

                TextColumn::make('name')
                    ->label(__('app.label.name'))
                    ->searchable()
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('theme')
                    ->label(__('app.label.application_theme'))
                    ->badge()
                    ->formatStateUsing(fn(?string $state): string => Application::themeLabel($state)),

                TextColumn::make('message')
                    ->label(__('app.label.message'))
                    ->wrap()
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('status')
                    ->label(__('app.label.status'))
                    ->badge()
                    ->color(fn(?string $state): string => Application::statusColor($state))
                    ->formatStateUsing(fn(?string $state): string => Application::statusLabel($state))
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('app.label.created'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('app.label.status'))
                    ->options(Application::getStatusOptions()),

                SelectFilter::make('theme')
                    ->label(__('app.label.application_theme'))
                    ->options(Application::getThemeOptions()),
            ])
            ->recordActions([
                ChangeApplicationStatusAction::make(),
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ChangeApplicationStatusAction::bulk(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}