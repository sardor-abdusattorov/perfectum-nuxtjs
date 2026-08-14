<?php

namespace App\Filament\Resources\CoverageLayers;

use App\Filament\Resources\CoverageLayers\Pages\CreateCoverageLayer;
use App\Filament\Resources\CoverageLayers\Pages\EditCoverageLayer;
use App\Filament\Resources\CoverageLayers\Pages\ListCoverageLayers;
use App\Filament\Resources\CoverageLayers\Schemas\CoverageLayerForm;
use App\Filament\Resources\CoverageLayers\Tables\CoverageLayersTable;
use App\Models\CoverageLayer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CoverageLayerResource extends Resource
{
    protected static ?string $model = CoverageLayer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    protected static ?string $recordTitleAttribute = 'key';

    public static function getNavigationGroup(): ?string
    {
        return __('app.group.offices');
    }

    public static function getModelLabel(): string
    {
        return __('app.label.coverage_layer_single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.label.coverage_layer_plural');
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
        return CoverageLayerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CoverageLayersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCoverageLayers::route('/'),
            'create' => CreateCoverageLayer::route('/create'),
            'edit' => EditCoverageLayer::route('/{record}/edit'),
        ];
    }
}
