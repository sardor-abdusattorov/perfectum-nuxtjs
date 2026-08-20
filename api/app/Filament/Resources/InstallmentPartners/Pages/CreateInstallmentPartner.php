<?php

namespace App\Filament\Resources\InstallmentPartners\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\InstallmentPartners\InstallmentPartnerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInstallmentPartner extends CreateRecord
{
    use GeneratesSlug;

    protected static string $resource = InstallmentPartnerResource::class;
}
