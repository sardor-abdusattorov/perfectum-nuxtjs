<?php

namespace App\Filament\Resources\DeviceCategories;

use App\Filament\Resources\DeviceCategories\Pages\CreateDeviceCategory;
use App\Filament\Resources\DeviceCategories\Pages\EditDeviceCategory;
use App\Filament\Resources\DeviceCategories\Pages\ListDeviceCategories;
use App\Filament\Resources\DeviceCategories\Schemas\DeviceCategoryForm;
use App\Filament\Resources\DeviceCategories\Tables\DeviceCategoriesTable;
use App\Models\DeviceCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DeviceCategoryResource extends Resource
{
    protected static ?string $model = DeviceCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $recordTitleAttribute = 'slug';

    public static function getNavigationGroup(): ?string
    {
        return __('app.group.devices');
    }

    public static function getModelLabel(): string
    {
        return __('app.label.device_category_single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.label.device_category_plural');
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
        return DeviceCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeviceCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDeviceCategories::route('/'),
            'create' => CreateDeviceCategory::route('/create'),
            'edit' => EditDeviceCategory::route('/{record}/edit'),
        ];
    }
}
