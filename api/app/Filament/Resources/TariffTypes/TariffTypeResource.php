<?php

namespace App\Filament\Resources\TariffTypes;

use App\Filament\Resources\TariffTypes\Pages\CreateTariffType;
use App\Filament\Resources\TariffTypes\Pages\EditTariffType;
use App\Filament\Resources\TariffTypes\Pages\ListTariffTypes;
use App\Filament\Resources\TariffTypes\Schemas\TariffTypeForm;
use App\Filament\Resources\TariffTypes\Tables\TariffTypesTable;
use App\Models\TariffType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TariffTypeResource extends Resource
{
    protected static ?string $model = TariffType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $recordTitleAttribute = 'slug';

    public static function getNavigationGroup(): ?string
    {
        return __('app.group.tariffs');
    }

    public static function getModelLabel(): string
    {
        return __('app.label.tariff_type_single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.label.tariff_type_plural');
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
        return TariffTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TariffTypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTariffTypes::route('/'),
            'create' => CreateTariffType::route('/create'),
            'edit' => EditTariffType::route('/{record}/edit'),
        ];
    }
}
