<?php

namespace App\Filament\Resources\ApplicationThemes\Pages;

use App\Filament\Resources\ApplicationThemes\ApplicationThemeResource;
use App\Filament\Resources\Concerns\GeneratesSlug;
use Filament\Resources\Pages\CreateRecord;

class CreateApplicationTheme extends CreateRecord
{
    use GeneratesSlug;

    protected static string $resource = ApplicationThemeResource::class;
}
