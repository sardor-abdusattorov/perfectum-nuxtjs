<?php

namespace App\Filament\Resources\PageSettings;

use App\Filament\Resources\PageSettings\Pages\EditPageSettings;
use App\Filament\Resources\PageSettings\Pages\ListPageSettings;
use App\Filament\Resources\PageSettings\Schemas\PageSettingsForm;
use App\Filament\Resources\PageSettings\Tables\PageSettingsTable;
use App\Models\PageSettings;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PageSettingsResource extends Resource
{
    protected static ?string $model = PageSettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected static ?string $recordTitleAttribute = 'key';

    public static function getNavigationGroup(): ?string
    {
        return __('app.group.content');
    }

    public static function getModelLabel(): string
    {
        return __('app.label.page_settings_single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.label.page_settings_plural');
    }

    public static function getNavigationSort(): int
    {
        return 3;
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::$model::count();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return PageSettingsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PageSettingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPageSettings::route('/'),
            'edit' => EditPageSettings::route('/{record}/edit'),
        ];
    }
}
