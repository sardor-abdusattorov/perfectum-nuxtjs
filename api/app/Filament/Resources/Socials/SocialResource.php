<?php

namespace App\Filament\Resources\Socials;

use App\Filament\Resources\Socials\Pages\CreateSocial;
use App\Filament\Resources\Socials\Pages\EditSocial;
use App\Filament\Resources\Socials\Pages\ListSocials;
use App\Filament\Resources\Socials\Pages\ViewSocial;
use App\Filament\Resources\Socials\Schemas\SocialForm;
use App\Filament\Resources\Socials\Tables\SocialsTable;
use App\Models\Social;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SocialResource extends Resource
{
    protected static ?string $model = Social::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShare;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return __('app.label.resources');
    }

    public static function getModelLabel(): string
    {
        return __('app.label.social_single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.label.social_plural');
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
        return SocialForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SocialsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSocials::route('/'),
            'create' => CreateSocial::route('/create'),
            'view' => ViewSocial::route('/{record}'),
            'edit' => EditSocial::route('/{record}/edit'),
        ];
    }
}
