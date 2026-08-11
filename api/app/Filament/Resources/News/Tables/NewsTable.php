<?php

namespace App\Filament\Resources\News\Tables;

use App\Enums\CategoryType;
use App\Filament\Support\CategoryFilter;
use App\Filament\Support\CrudActions;
use App\Filament\Support\StatusColumn;
use App\Filament\Support\StatusFilter;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                ImageColumn::make('image')
                    ->label(__('app.label.image'))
                    ->disk('public')
                    ->square(),

                TextColumn::make('title')
                    ->label(__('app.label.title'))
                    ->searchable()
                    ->wrap()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label(__('app.label.category'))
                    ->badge()
                    ->placeholder('—'),

                TextColumn::make('published_at')
                    ->label(__('app.label.published_at'))
                    ->date()
                    ->sortable(),

                StatusColumn::make(),
            ])
            ->filters([
                CategoryFilter::make(CategoryType::News),

                StatusFilter::make(),
            ])
            ->recordActions(CrudActions::record())
            ->toolbarActions(CrudActions::bulk());
    }
}
