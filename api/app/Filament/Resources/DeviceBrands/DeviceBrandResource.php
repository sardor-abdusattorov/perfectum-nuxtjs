<?php

namespace App\Filament\Resources\DeviceBrands;

use App\Filament\Resources\DeviceBrands\Pages\CreateDeviceBrand;
use App\Filament\Resources\DeviceBrands\Pages\EditDeviceBrand;
use App\Filament\Resources\DeviceBrands\Pages\ListDeviceBrands;
use App\Filament\Resources\DeviceBrands\Pages\ViewDeviceBrand;
use App\Filament\Resources\DeviceBrands\Schemas\DeviceBrandForm;
use App\Filament\Resources\DeviceBrands\Tables\DeviceBrandsTable;
use App\Models\DeviceBrand;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DeviceBrandResource extends Resource
{
    protected static ?string $model = DeviceBrand::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return __('app.group.devices');
    }

    public static function getModelLabel(): string
    {
        return __('app.label.device_brand_single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.label.device_brand_plural');
    }

    public static function getNavigationSort(): int
    {
        return 3;
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::$model::count();
    }

    public static function form(Schema $schema): Schema
    {
        return DeviceBrandForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeviceBrandsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDeviceBrands::route('/'),
            'create' => CreateDeviceBrand::route('/create'),
            'view' => ViewDeviceBrand::route('/{record}'),
            'edit' => EditDeviceBrand::route('/{record}/edit'),
        ];
    }
}
