<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('id');
    }

    public static function cacheKey(): string
    {
        return 'socials.published';
    }
}
