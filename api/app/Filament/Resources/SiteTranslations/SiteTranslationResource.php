<?php

namespace App\Filament\Resources\SiteTranslations;

use App\Filament\Resources\SiteTranslations\Pages\CreateSiteTranslation;
use App\Filament\Resources\SiteTranslations\Pages\EditSiteTranslation;
use App\Filament\Resources\SiteTranslations\Pages\ListSiteTranslations;
use App\Filament\Resources\SiteTranslations\Pages\ViewSiteTranslation;
use App\Filament\Resources\SiteTranslations\Schemas\SiteTranslationForm;
use App\Filament\Resources\SiteTranslations\Tables\SiteTranslationsTable;
use App\Models\SiteTranslation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SiteTranslationResource extends Resource
{
    protected static ?string $model = SiteTranslation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLanguage;

    public static function getNavigationGroup(): ?string
    {
        return __('app.label.administration');
    }

    public static function getModelLabel(): string
    {
        return __('app.label.site_translations_single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.label.site_translations_plural');
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
        return SiteTranslationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SiteTranslationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSiteTranslations::route('/'),
            'create' => CreateSiteTranslation::route('/create'),
            'view' => ViewSiteTranslation::route('/{record}'),
            'edit' => EditSiteTranslation::route('/{record}/edit'),
        ];
    }
}
