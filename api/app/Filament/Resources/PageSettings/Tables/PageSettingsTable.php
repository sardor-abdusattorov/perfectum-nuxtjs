<?php

namespace App\Filament\Resources\PageSettings\Tables;

use App\Filament\Support\Tables;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PageSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('key')
            ->paginated(false)
            ->columns([
                TextColumn::make('key')
                    ->label(__('app.label.page'))
                    ->badge()
                    ->searchable(),

                TextColumn::make('meta_title')
                    ->label(__('app.label.meta_title'))
                    ->placeholder('—')
                    ->wrap(),

                TextColumn::make('meta_description')
                    ->label(__('app.label.meta_description'))
                    ->placeholder('—')
                    ->limit(60)
                    ->toggleable(),

                IconColumn::make('is_indexed')
                    ->label(__('app.label.is_indexed'))
                    ->boolean(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions(Tables::bulkActions());
    }
}
