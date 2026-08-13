<?php

namespace App\Filament\Resources\Applications\Tables;

use App\Models\Application;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;

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
                    ->formatStateUsing(fn (string $state): string => Application::getThemeOptions()[$state] ?? $state),

                TextColumn::make('message')
                    ->label(__('app.label.message'))
                    ->limit(60)
                    ->wrap()
                    ->searchable()
                    ->placeholder('—'),

                SelectColumn::make('status')
                    ->label(__('app.label.status'))
                    ->options(Application::getStatusOptions())
                    ->selectablePlaceholder(false)
                    ->disabled(fn (Application $record): bool => Gate::denies('update', $record)),

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
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
