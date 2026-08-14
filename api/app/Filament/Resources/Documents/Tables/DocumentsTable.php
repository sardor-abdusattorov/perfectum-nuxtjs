<?php

namespace App\Filament\Resources\Documents\Tables;

use App\Filament\Support\Tables;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentFile;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with('files'))
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
                    ->state(fn (Document $record): array => $record->files
                        ->map(fn (DocumentFile $file): string => blank($file->language)
                            ? __('app.label.language_all')
                            : __("app.label.{$file->language}"))
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
