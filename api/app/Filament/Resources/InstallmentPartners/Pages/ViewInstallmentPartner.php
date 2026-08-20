<?php

namespace App\Filament\Resources\InstallmentPartners\Pages;

use App\Filament\Resources\InstallmentPartners\InstallmentPartnerResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewInstallmentPartner extends ViewRecord
{
    protected static string $resource = InstallmentPartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
