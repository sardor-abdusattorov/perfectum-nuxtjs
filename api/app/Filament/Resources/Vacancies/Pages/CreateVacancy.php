<?php

namespace App\Filament\Resources\Vacancies\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\Vacancies\VacancyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVacancy extends CreateRecord
{
    use GeneratesSlug;

    protected static string $resource = VacancyResource::class;
}
