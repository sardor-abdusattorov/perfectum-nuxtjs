<?php

namespace App\Filament\Resources\News\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\News\NewsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNews extends CreateRecord
{
    use GeneratesSlug;

    protected static string $resource = NewsResource::class;
}
