<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Enums\Network;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToNetwork
{
    /**
     * Records marked as shared belong to both sections, so a section query
     * always asks for its own network plus the shared ones.
     */
    public function scopeForNetwork(Builder $query, ?Network $network): Builder
    {
        if ($network === null || $network === Network::Both) {
            return $query;
        }

        return $query->whereIn('network', [$network, Network::Both]);
    }
}
