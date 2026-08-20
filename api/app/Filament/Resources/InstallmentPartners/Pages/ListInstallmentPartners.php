<?php

namespace App\Filament\Resources\InstallmentPartners\Pages;

use App\Filament\Resources\InstallmentPartners\InstallmentPartnerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInstallmentPartners extends ListRecords
{
    protected static string $resource = InstallmentPartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
