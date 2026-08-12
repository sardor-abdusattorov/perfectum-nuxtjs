<?php

namespace App\Filament\Resources\Regions\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\Regions\RegionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRegion extends CreateRecord
{
    use GeneratesSlug;

    protected static string $resource = RegionResource::class;
}
