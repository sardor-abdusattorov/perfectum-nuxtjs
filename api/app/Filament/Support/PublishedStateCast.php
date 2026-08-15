<?php

declare(strict_types=1);

namespace App\Filament\Support;

use Filament\Schemas\Components\StateCasts\Contracts\StateCast;

/**
 * Everywhere on the site "not explicitly false" counts as published. Toggle's
 * own boolean cast reads an absent value as off, so filling a form with a row
 * saved before the switch existed showed it unpublished — and the next save
 * made that real, silently taking the row off the site.
 */
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
