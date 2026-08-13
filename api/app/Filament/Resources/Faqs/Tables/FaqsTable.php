<?php

namespace App\Filament\Resources\Faqs\Tables;

use App\Filament\Support\Tables;
use App\Models\FaqCategory;
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

                TextColumn::make('pages')
                    ->label(__('app.label.faq_pages'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __("app.faq_page.{$state}")),

                Tables::statusColumn(),
            ])
            ->filters([
                Tables::categoryFilter(FaqCategory::class),

                Tables::statusFilter(),
            ])
            ->recordActions(Tables::actions())
            ->toolbarActions(Tables::bulkActions());
    }
}
