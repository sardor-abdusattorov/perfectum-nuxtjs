<?php

namespace App\Filament\Resources\Faqs\Tables;

use App\Enums\CategoryType;
use App\Filament\Support\CategoryFilter;
use App\Filament\Support\CrudActions;
use App\Filament\Support\StatusColumn;
use App\Filament\Support\StatusFilter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FaqsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->columns([
                TextColumn::make('question')
                    ->label(__('app.label.question'))
                    ->searchable()
                    ->wrap()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label(__('app.label.category'))
                    ->badge()
                    ->placeholder('—'),

                TextColumn::make('sort')
                    ->label(__('app.label.sort'))
                    ->sortable(),

                StatusColumn::make(),
            ])
            ->filters([
                CategoryFilter::make(CategoryType::Faq),

                StatusFilter::make(),
            ])
            ->recordActions(CrudActions::record())
            ->toolbarActions(CrudActions::bulk());
    }
}
