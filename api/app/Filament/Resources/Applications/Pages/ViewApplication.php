<?php

namespace App\Filament\Resources\Applications\Pages;

use App\Filament\Resources\Applications\ApplicationResource;
use App\Models\Application;
use Filament\Resources\Pages\ViewRecord;

class ViewApplication extends ViewRecord
{
    protected static string $resource = ApplicationResource::class;

    /**
     * Opening a new application marks it processed, the way an inbox
     * marks a letter read — the navigation badge counts only new ones.
     */
    public function mount(int|string $record): void
    {
        parent::mount($record);

        /** @var Application $application */
        $application = $this->getRecord();

        if ($application->status === Application::STATUS_NEW) {
            $application->update(['status' => Application::STATUS_PROCESSED]);
        }
    }
}
