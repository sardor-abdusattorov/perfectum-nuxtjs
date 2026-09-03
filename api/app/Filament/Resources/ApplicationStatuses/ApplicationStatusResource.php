<?php

namespace App\Filament\Resources\ApplicationStatuses;

use App\Filament\Resources\ApplicationStatuses\Pages\CreateApplicationStatus;
use App\Filament\Resources\ApplicationStatuses\Pages\EditApplicationStatus;
use App\Filament\Resources\ApplicationStatuses\Pages\ListApplicationStatuses;
use App\Filament\Resources\ApplicationStatuses\Pages\ViewApplicationStatus;
use App\Filament\Resources\ApplicationStatuses\Schemas\ApplicationStatusForm;
use App\Filament\Resources\ApplicationStatuses\Tables\ApplicationStatusesTable;
use App\Models\ApplicationStatus;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ApplicationStatusResource extends Resource
{
    protected static ?string $model = ApplicationStatus::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFlag;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return __('app.group.applications');
    }

    public static function getModelLabel(): string
    {
        return __('app.label.application_status_single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.label.application_status_plural');
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
        return ApplicationStatusForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApplicationStatusesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApplicationStatuses::route('/'),
            'create' => CreateApplicationStatus::route('/create'),
            'view' => ViewApplicationStatus::route('/{record}'),
            'edit' => EditApplicationStatus::route('/{record}/edit'),
        ];
    }
}
