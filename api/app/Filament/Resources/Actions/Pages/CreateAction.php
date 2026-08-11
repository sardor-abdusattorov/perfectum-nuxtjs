<?php

namespace App\Filament\Resources\Actions\Pages;

use App\Filament\Resources\Actions\ActionResource;
use App\Filament\Resources\Concerns\GeneratesSlug;
use Filament\Resources\Pages\CreateRecord;

class CreateAction extends CreateRecord
{
    use GeneratesSlug;

    protected static string $resource = ActionResource::class;
}
