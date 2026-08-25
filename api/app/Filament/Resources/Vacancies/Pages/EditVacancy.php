<?php

namespace App\Filament\Resources\Vacancies\Pages;

use App\Filament\Resources\Concerns\GeneratesSlug;
use App\Filament\Resources\Vacancies\VacancyResource;
use App\Filament\Support\PreviewAction;
use App\Models\Vacancy;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditVacancy extends EditRecord
{
    use GeneratesSlug;

    protected static string $resource = VacancyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PreviewAction::make(fn (Vacancy $record): string => "/careers/{$record->slug}"),
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
