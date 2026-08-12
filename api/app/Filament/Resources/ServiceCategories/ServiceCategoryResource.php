<?php

namespace App\Filament\Resources\ServiceCategories;

use App\Filament\Resources\ServiceCategories\Pages\CreateServiceCategory;
use App\Filament\Resources\ServiceCategories\Pages\EditServiceCategory;
use App\Filament\Resources\ServiceCategories\Pages\ListServiceCategories;
use App\Filament\Resources\ServiceCategories\Schemas\ServiceCategoryForm;
use App\Filament\Resources\ServiceCategories\Tables\ServiceCategoriesTable;
use App\Models\ServiceCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ServiceCategoryResource extends Resource
{
    protected static ?string $model = ServiceCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $recordTitleAttribute = 'slug';

    public static function getNavigationGroup(): ?string
    {
        return __('app.group.services');
    }

    public static function getModelLabel(): string
    {
        return __('app.label.service_category_single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.label.service_category_plural');
    }

    public static function getNavigationSort(): int
    {
        return 1;
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::$model::count();
    }

    public static function form(Schema $schema): Schema
    {
        return ServiceCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServiceCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServiceCategories::route('/'),
            'create' => CreateServiceCategory::route('/create'),
            'edit' => EditServiceCategory::route('/{record}/edit'),
        ];
    }
}
