<?php

namespace App\Filament\Resources\Tenders\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\Tenders\TenderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTender extends CreateRecord
{
    use GeneratesSlug;

    protected static string $resource = TenderResource::class;
}
