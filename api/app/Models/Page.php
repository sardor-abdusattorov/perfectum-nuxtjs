<?php

namespace App\Models;

use App\Models\Concerns\CleansUpAttachedFiles;
use App\Models\Concerns\HasMediaUrl;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use CleansUpAttachedFiles;
    use HasMediaUrl;
    use HasTranslations;
    use Publishable;

    public const CACHE_TTL = 86400;

    protected $table = 'pages';

    protected $fillable = [
        'slug',
        'title',
        'content',
        'image',
        'meta_title',
        'meta_description',
        'redirect_from',
        'status',
    ];

    public $translatable = ['title', 'content', 'meta_title', 'meta_description'];

    protected $casts = [
        'redirect_from' => 'array',
        'status' => 'boolean',
    ];

    /**
     * The admin pastes old addresses however the ad carried them — with the
     * domain, a locale prefix, slashes on either end. Boiled down to the bare
     * path here, the redirect map only ever has one spelling to match against.
     */
    protected static function booted(): void
    {
        static::saving(function (self $page): void {
            if (! $page->isDirty('redirect_from')) {
                return;
            }

            $paths = collect($page->redirect_from ?? [])
                ->map(function (string $path): string {
                    $path = preg_replace('#^https?://[^/]+#i', '', trim($path)) ?? '';
                    $path = strtok($path, '?#') ?: '';
                    $path = trim($path, '/');

                    return preg_replace('#^(ru|uz)(/|$)#', '', $path) ?? '';
                })
                ->filter()
                ->unique()
                ->values()
                ->all();

            $page->redirect_from = $paths ?: null;
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function cacheKey(string $slug, string $locale): string
    {
        return "pages.{$slug}.{$locale}";
    }

    public static function redirectsCacheKey(): string
    {
        return 'pages.redirects';
    }

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        return static::query()->published()->where('slug', $value)->first();
    }
}
