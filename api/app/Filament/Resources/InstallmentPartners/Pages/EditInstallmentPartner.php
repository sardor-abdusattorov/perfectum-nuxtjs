<?php

namespace App\Filament\Resources\InstallmentPartners\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\InstallmentPartners\InstallmentPartnerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInstallmentPartner extends EditRecord
{
    use GeneratesSlug;

    protected static string $resource = InstallmentPartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
