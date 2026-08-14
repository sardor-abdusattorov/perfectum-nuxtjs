<?php

namespace App\Filament\Resources\Documents\Tables;

use App\Filament\Support\Tables;
use App\Models\Document;
use App\Models\DocumentCategory;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->columns([
                TextColumn::make('name')
                    ->label(__('app.label.name'))
                    ->searchable()
                    ->wrap()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label(__('app.label.category'))
                    ->badge()
                    ->placeholder('—'),

                TextColumn::make('languages')
                    ->label(__('app.label.languages'))
                    ->badge()
                    ->state(fn (Document $record): array => collect($record->getTranslations('file'))
                        ->filter()
                        ->keys()
                        ->map(fn (string $locale): string => __("app.label.{$locale}"))
                        ->all())
                    ->placeholder('—'),

                TextColumn::make('sort')
                    ->label(__('app.label.sort'))
                    ->sortable(),

                Tables::statusColumn(),
            ])
            ->filters([
                Tables::categoryFilter(DocumentCategory::class),

                Tables::statusFilter(),
            ])
            ->recordActions(Tables::actions())
            ->toolbarActions(Tables::bulkActions());
    }
}
