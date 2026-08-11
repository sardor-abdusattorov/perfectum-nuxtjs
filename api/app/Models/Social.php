<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Social extends Model
{
    public const CACHE_TTL = 86400;

    protected $table = 'socials';

    protected $fillable = [
        'name',
        'icon',
        'url',
        'sort',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    public static function cacheKey(): string
    {
        return 'socials.published';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, self>
     */
    public static function published(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember(
            static::cacheKey(),
            static::CACHE_TTL,
            fn (): \Illuminate\Database\Eloquent\Collection => static::query()
                ->published()
                ->orderBy('sort')
                ->get(),
        );
    }
}
