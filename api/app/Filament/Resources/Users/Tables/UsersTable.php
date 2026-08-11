<?php

namespace App\Filament\Resources\Users\Tables;

use App\Filament\Support\CrudActions;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('app.label.name'))
                    ->searchable(),

                TextColumn::make('email')
                    ->label(__('app.label.email'))
                    ->searchable(),

                TextColumn::make('roles.name')
                    ->label(__('app.label.roles'))
                    ->badge()
                    ->color('primary'),

                TextColumn::make('created_at')
                    ->label(__('app.label.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('app.label.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

            ])
            ->recordActions(CrudActions::record())
            ->toolbarActions(CrudActions::bulk());
    }
}
