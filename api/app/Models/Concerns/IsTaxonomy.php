<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Enums\Network;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

trait IsTaxonomy
{
    public const CACHE_TTL = 86400;

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('id');
    }

    public static function cacheKey(string $locale): string
    {
        return 'taxonomy.'.static::make()->getTable().'.'.$locale;
    }

    public static function publicCacheKey(string $locale, ?Network $network): string
    {
        return static::cacheKey($locale).'.public.'.($network?->value ?? 'all');
    }

    /**
     * @return array<int, string>
     */
    public static function options(): array
    {
        return Cache::remember(
            static::cacheKey(app()->getLocale()),
            static::CACHE_TTL,
            fn (): array => static::query()
                ->ordered()
                ->get(['id', 'name'])
                ->mapWithKeys(fn (self $row): array => [$row->getKey() => $row->name])
                ->all(),
        );
    }
}
