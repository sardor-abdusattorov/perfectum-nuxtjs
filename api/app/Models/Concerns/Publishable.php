<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait Publishable
{
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    /**
     * Route binding never reaches an unpublished record, so a draft is a 404
     * for the site without every controller checking for it.
     */
    public function resolveRouteBinding($value, $field = null): ?Model
    {
        return static::query()
            ->published()
            ->where($field ?? $this->getRouteKeyName(), $value)
            ->first();
    }
}
