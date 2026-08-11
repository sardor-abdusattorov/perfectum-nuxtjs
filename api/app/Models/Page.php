<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasTranslations;

    public const CACHE_TTL = 86400;

    protected $table = 'pages';

    protected $fillable = [
        'slug',
        'title',
        'content',
        'image',
        'meta_title',
        'meta_description',
        'status',
    ];

    public $translatable = ['title', 'content', 'meta_title', 'meta_description'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    public static function cacheKey(string $slug): string
    {
        return "pages.{$slug}";
    }

    public static function findPublished(string $slug): ?self
    {
        return Cache::remember(
            static::cacheKey($slug),
            static::CACHE_TTL,
            fn (): ?self => static::query()->published()->where('slug', $slug)->first(),
        );
    }

    public function imageUrl(): ?string
    {
        if (blank($this->image)) {
            return null;
        }

        return str_starts_with($this->image, 'http')
            ? $this->image
            : Storage::disk('public')->url($this->image);
    }
}
