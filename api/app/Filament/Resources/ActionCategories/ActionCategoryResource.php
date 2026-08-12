<?php

namespace App\Filament\Resources\ActionCategories;

use App\Filament\Resources\ActionCategories\Pages\CreateActionCategory;
use App\Filament\Resources\ActionCategories\Pages\EditActionCategory;
use App\Filament\Resources\ActionCategories\Pages\ListActionCategories;
use App\Filament\Resources\ActionCategories\Schemas\ActionCategoryForm;
use App\Filament\Resources\ActionCategories\Tables\ActionCategoriesTable;
use App\Models\ActionCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ActionCategoryResource extends Resource
{
    protected static ?string $model = ActionCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $recordTitleAttribute = 'slug';

    public static function getNavigationGroup(): ?string
    {
        return __('app.group.resources');
    }

    public static function getModelLabel(): string
    {
        return __('app.label.action_category_single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.label.action_category_plural');
    }

    public static function getNavigationSort(): int
    {
        return 4;
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::$model::count();
    }

    public static function form(Schema $schema): Schema
    {
        return ActionCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ActionCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActionCategories::route('/'),
            'create' => CreateActionCategory::route('/create'),
            'edit' => EditActionCategory::route('/{record}/edit'),
        ];
    }
}
