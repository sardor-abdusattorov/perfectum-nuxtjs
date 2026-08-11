<?php

namespace App\Filament\Resources\Pages\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\Pages\PageResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateRecord
{
    use GeneratesSlug;

    protected static string $resource = PageResource::class;
}
