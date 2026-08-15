<?php

namespace App\Filament\Resources\FreeNumberFilters;

use App\Filament\Resources\FreeNumberFilters\Pages\CreateFreeNumberFilter;
use App\Filament\Resources\FreeNumberFilters\Pages\EditFreeNumberFilter;
use App\Filament\Resources\FreeNumberFilters\Pages\ListFreeNumberFilters;
use App\Filament\Resources\FreeNumberFilters\Pages\ViewFreeNumberFilter;
use App\Filament\Resources\FreeNumberFilters\Schemas\FreeNumberFilterForm;
use App\Filament\Resources\FreeNumberFilters\Tables\FreeNumberFiltersTable;
use App\Models\FreeNumberFilter;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FreeNumberFilterResource extends Resource
{
    protected static ?string $model = FreeNumberFilter::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHashtag;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return __('app.group.resources');
    }

    public static function getModelLabel(): string
    {
        return __('app.label.free_number_filter_single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.label.free_number_filter_plural');
    }

    public static function getNavigationSort(): int
    {
        return 11;
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::$model::count();
    }

    public static function form(Schema $schema): Schema
    {
        return FreeNumberFilterForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FreeNumberFiltersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFreeNumberFilters::route('/'),
            'create' => CreateFreeNumberFilter::route('/create'),
            'view' => ViewFreeNumberFilter::route('/{record}'),
            'edit' => EditFreeNumberFilter::route('/{record}/edit'),
        ];
    }
}
