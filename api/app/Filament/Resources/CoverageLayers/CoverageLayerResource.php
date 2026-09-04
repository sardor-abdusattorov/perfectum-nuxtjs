<?php

namespace App\Filament\Resources\CoverageLayers;

use App\Filament\Resources\CoverageLayers\Pages\CreateCoverageLayer;
use App\Filament\Resources\CoverageLayers\Pages\EditCoverageLayer;
use App\Filament\Resources\CoverageLayers\Pages\ListCoverageLayers;
use App\Filament\Resources\CoverageLayers\Pages\ViewCoverageLayer;
use App\Filament\Resources\CoverageLayers\Schemas\CoverageLayerForm;
use App\Filament\Resources\CoverageLayers\Tables\CoverageLayersTable;
use App\Models\CoverageLayer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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

    /**
     * The panel never shows the geometry — the form is the archive and a few
     * words around it. But Filament fills the form from every attribute of the
     * record, so the whole contour set travelled into the Livewire component
     * and back on each request: six megabytes in a payload Livewire refuses
     * past eight. Leaving the column out of the query is what keeps the page
     * open; `has_shapes` carries the one thing the list actually asks of it.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->select(['id', 'key', 'name', 'color', 'file', 'features', 'sort', 'status', 'created_at', 'updated_at'])
            ->selectRaw('geojson is not null as has_shapes')
            ->withCasts(['has_shapes' => 'boolean']);
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
            'view' => ViewCoverageLayer::route('/{record}'),
            'edit' => EditCoverageLayer::route('/{record}/edit'),
        ];
    }
}
