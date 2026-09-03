<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Enums\Network;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToNetwork
{
    public function scopeForNetwork(Builder $query, ?Network $network): Builder
    {
        if ($network === null || $network === Network::Both) {
            return $query;
        }

        return $query->whereIn('network', [$network, Network::Both]);
    }
}
