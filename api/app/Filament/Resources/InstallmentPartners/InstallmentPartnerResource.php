<?php

namespace App\Filament\Resources\InstallmentPartners;

use App\Filament\Resources\InstallmentPartners\Pages\CreateInstallmentPartner;
use App\Filament\Resources\InstallmentPartners\Pages\EditInstallmentPartner;
use App\Filament\Resources\InstallmentPartners\Pages\ListInstallmentPartners;
use App\Filament\Resources\InstallmentPartners\Pages\ViewInstallmentPartner;
use App\Filament\Resources\InstallmentPartners\Schemas\InstallmentPartnerForm;
use App\Filament\Resources\InstallmentPartners\Tables\InstallmentPartnersTable;
use App\Models\InstallmentPartner;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InstallmentPartnerResource extends Resource
{
    protected static ?string $model = InstallmentPartner::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return __('app.group.devices');
    }

    public static function getModelLabel(): string
    {
        return __('app.label.installment_partner_single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.label.installment_partner_plural');
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
        return InstallmentPartnerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InstallmentPartnersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInstallmentPartners::route('/'),
            'create' => CreateInstallmentPartner::route('/create'),
            'view' => ViewInstallmentPartner::route('/{record}'),
            'edit' => EditInstallmentPartner::route('/{record}/edit'),
        ];
    }
}
