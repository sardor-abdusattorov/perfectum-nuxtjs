<?php

namespace App\Filament\Resources\Tariffs;

use App\Filament\Resources\Tariffs\Pages\CreateTariff;
use App\Filament\Resources\Tariffs\Pages\EditTariff;
use App\Filament\Resources\Tariffs\Pages\ListTariffs;
use App\Filament\Resources\Tariffs\Pages\ViewTariff;
use App\Filament\Resources\Tariffs\Schemas\TariffForm;
use App\Filament\Resources\Tariffs\Tables\TariffsTable;
use App\Models\Tariff;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TariffResource extends Resource
{
    protected static ?string $model = Tariff::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static ?string $recordTitleAttribute = 'slug';

    public static function getNavigationGroup(): ?string
    {
        return __('app.group.tariffs');
    }

    public static function getModelLabel(): string
    {
        return __('app.label.tariff_single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.label.tariff_plural');
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
        return TariffForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TariffsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTariffs::route('/'),
            'create' => CreateTariff::route('/create'),
            'view' => ViewTariff::route('/{record}'),
            'edit' => EditTariff::route('/{record}/edit'),
        ];
    }
}
