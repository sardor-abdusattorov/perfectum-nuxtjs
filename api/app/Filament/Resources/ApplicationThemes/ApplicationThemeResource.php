<?php

namespace App\Filament\Resources\ApplicationThemes;

use App\Filament\Resources\ApplicationThemes\Pages\CreateApplicationTheme;
use App\Filament\Resources\ApplicationThemes\Pages\EditApplicationTheme;
use App\Filament\Resources\ApplicationThemes\Pages\ListApplicationThemes;
use App\Filament\Resources\ApplicationThemes\Pages\ViewApplicationTheme;
use App\Filament\Resources\ApplicationThemes\Schemas\ApplicationThemeForm;
use App\Filament\Resources\ApplicationThemes\Tables\ApplicationThemesTable;
use App\Models\ApplicationTheme;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ApplicationThemeResource extends Resource
{
    protected static ?string $model = ApplicationTheme::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return __('app.group.applications');
    }

    public static function getModelLabel(): string
    {
        return __('app.label.application_theme_single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.label.application_theme_plural');
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
        return ApplicationThemeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApplicationThemesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApplicationThemes::route('/'),
            'create' => CreateApplicationTheme::route('/create'),
            'view' => ViewApplicationTheme::route('/{record}'),
            'edit' => EditApplicationTheme::route('/{record}/edit'),
        ];
    }
}
