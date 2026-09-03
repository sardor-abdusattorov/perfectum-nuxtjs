<?php

namespace App\Filament\Resources\Applications\Tables;

use App\Filament\Resources\Applications\Actions\ChangeApplicationStatusAction;
use App\Models\Application;
use App\Models\ApplicationStatus;
use App\Models\ApplicationTheme;
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

                TextColumn::make('theme.name')
                    ->label(__('app.label.application_theme'))
                    ->badge()
                    ->placeholder('—'),

                TextColumn::make('message')
                    ->label(__('app.label.message'))
                    ->wrap()
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('status.name')
                    ->label(__('app.label.status'))
                    ->badge()
                    ->color(fn (Application $record): string => $record->status?->color ?? 'gray')
                    ->placeholder('—')
                    ->sortable(),

                TextColumn::make('processed_at')
                    ->label(__('app.label.handling_time'))
                    ->state(fn (Application $record): ?string => Application::readableHandlingTime($record->handlingSeconds()))
                    ->placeholder('—'),

                TextColumn::make('notes.body')
                    ->label(__('app.label.note_last'))
                    ->state(fn (Application $record): ?string => $record->notes->first()?->body)
                    ->wrap()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label(__('app.label.created'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('status_id')
                    ->label(__('app.label.status'))
                    ->options(ApplicationStatus::options()),

                SelectFilter::make('theme_id')
                    ->label(__('app.label.application_theme'))
                    ->options(ApplicationTheme::options()),
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
