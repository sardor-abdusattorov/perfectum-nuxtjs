<?php

declare(strict_types=1);

namespace App\Filament\Support;

use Filament\Schemas\Components\StateCasts\Contracts\StateCast;

class PublishedStateCast implements StateCast
{
    public function get(mixed $state): bool
    {
        return $this->isPublished($state);
    }

    public function set(mixed $state): bool
    {
        return $this->isPublished($state);
    }

    private function isPublished(mixed $state): bool
    {
        return $state !== false && $state !== 0 && $state !== '0';
    }
}
