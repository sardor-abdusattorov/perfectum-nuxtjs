<?php

namespace App\Filament\Resources\TariffFiles;

use App\Filament\Resources\TariffFiles\Pages\CreateTariffFile;
use App\Filament\Resources\TariffFiles\Pages\EditTariffFile;
use App\Filament\Resources\TariffFiles\Pages\ListTariffFiles;
use App\Filament\Resources\TariffFiles\Pages\ViewTariffFile;
use App\Filament\Resources\TariffFiles\Schemas\TariffFileForm;
use App\Filament\Resources\TariffFiles\Tables\TariffFilesTable;
use App\Models\TariffFile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TariffFileResource extends Resource
{
    protected static ?string $model = TariffFile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return __('app.group.tariffs');
    }

    public static function getModelLabel(): string
    {
        return __('app.label.tariff_file_single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.label.tariff_file_plural');
    }

    public static function getNavigationSort(): int
    {
        return 4;
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::$model::count();
    }

    public static function form(Schema $schema): Schema
    {
        return TariffFileForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TariffFilesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTariffFiles::route('/'),
            'create' => CreateTariffFile::route('/create'),
            'view' => ViewTariffFile::route('/{record}'),
            'edit' => EditTariffFile::route('/{record}/edit'),
        ];
    }
}
