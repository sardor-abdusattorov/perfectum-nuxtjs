<?php

namespace App\Filament\Resources\Services\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\Services\ServiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateService extends CreateRecord
{
    use GeneratesSlug;

    protected static string $resource = ServiceResource::class;

    protected function slugSource(): string
    {
        return 'name';
    }
}
