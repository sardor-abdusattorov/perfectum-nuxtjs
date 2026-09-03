<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Support\PreviewToken;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait Publishable
{
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        $field ??= $this->getRouteKeyName();

        $published = static::query()->published()->where($field, $value)->first();

        if ($published !== null || ! PreviewToken::requested()) {
            return $published;
        }

        $draft = static::query()->where($field, $value)->first();

        return ($draft !== null && PreviewToken::allows($draft)) ? $draft : null;
    }
}
