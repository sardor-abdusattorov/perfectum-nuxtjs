<?php

namespace App\Filament\Resources\Services\Tables;

use App\Enums\CategoryType;
use App\Filament\Support\CategoryFilter;
use App\Filament\Support\CrudActions;
use App\Filament\Support\StatusColumn;
use App\Filament\Support\StatusFilter;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->reorderable('sort')
            ->columns([
                ImageColumn::make('icon')
                    ->label(__('app.label.icon'))
                    ->disk('public')
                    ->square(),

                TextColumn::make('name')
                    ->label(__('app.label.name'))
                    ->searchable()
                    ->weight('bold')
                    ->wrap()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label(__('app.label.category'))
                    ->badge()
                    ->placeholder('—'),

                TextColumn::make('ussd')
                    ->label(__('app.label.ussd'))
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),

                StatusColumn::make(),
            ])
            ->filters([
                CategoryFilter::make(CategoryType::Service),

                StatusFilter::make(),
            ])
            ->recordActions(CrudActions::record())
            ->toolbarActions(CrudActions::bulk());
    }
}
