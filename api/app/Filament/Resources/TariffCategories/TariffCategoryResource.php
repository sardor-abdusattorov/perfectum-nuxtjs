<?php

namespace App\Filament\Resources\TariffCategories;

use App\Filament\Resources\TariffCategories\Pages\CreateTariffCategory;
use App\Filament\Resources\TariffCategories\Pages\EditTariffCategory;
use App\Filament\Resources\TariffCategories\Pages\ListTariffCategories;
use App\Filament\Resources\TariffCategories\Schemas\TariffCategoryForm;
use App\Filament\Resources\TariffCategories\Tables\TariffCategoriesTable;
use App\Models\TariffCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TariffCategoryResource extends Resource
{
    protected static ?string $model = TariffCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $recordTitleAttribute = 'slug';

    public static function getNavigationGroup(): ?string
    {
        return __('app.group.tariffs');
    }

    public static function getModelLabel(): string
    {
        return __('app.label.tariff_category_single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.label.tariff_category_plural');
    }

    public static function getNavigationSort(): int
    {
        return 2;
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::$model::count();
    }

    public static function form(Schema $schema): Schema
    {
        return TariffCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TariffCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTariffCategories::route('/'),
            'create' => CreateTariffCategory::route('/create'),
            'edit' => EditTariffCategory::route('/{record}/edit'),
        ];
    }
}
