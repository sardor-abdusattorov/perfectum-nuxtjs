<?php

namespace App\Filament\Resources\Applications;

use App\Filament\Resources\Applications\Pages\ListApplications;
use App\Filament\Resources\Applications\Pages\ViewApplication;
use App\Filament\Resources\Applications\Schemas\ApplicationInfolist;
use App\Filament\Resources\Applications\Tables\ApplicationsTable;
use App\Models\Application;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInbox;

    public static function getNavigationGroup(): ?string
    {
        return __('app.group.applications');
    }

    public static function getModelLabel(): string
    {
        return __('app.label.application_single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.label.application_plural');
    }

    public static function getNavigationSort(): int
    {
        return 1;
    }

    public static function getNavigationBadge(): ?string
    {
        $new = static::$model::query()->whereNull('processed_at')->count();

        return $new > 0 ? (string) $new : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function infolist(Schema $schema): Schema
    {
        return ApplicationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApplicationsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApplications::route('/'),
            'view' => ViewApplication::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }
}
