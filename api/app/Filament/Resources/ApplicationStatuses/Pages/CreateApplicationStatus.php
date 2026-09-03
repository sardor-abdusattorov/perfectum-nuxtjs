<?php

namespace App\Filament\Resources\ApplicationStatuses\Pages;

use App\Filament\Resources\ApplicationStatuses\ApplicationStatusResource;
use App\Filament\Resources\Concerns\GeneratesSlug;
use Filament\Resources\Pages\CreateRecord;

class CreateApplicationStatus extends CreateRecord
{
    use GeneratesSlug;

    protected static string $resource = ApplicationStatusResource::class;
}
