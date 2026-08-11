<?php

namespace App\Filament\Support;

use App\Enums\PublishedStatus;
use Filament\Tables\Filters\SelectFilter;

class StatusFilter
{
    public static function make(string $field = 'status'): SelectFilter
    {
        return SelectFilter::make($field)
            ->label(__('app.label.status'))
            ->options(PublishedStatus::getStatusOptions());
    }
}
