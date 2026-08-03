<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Translatable\HasTranslations;

class SiteTranslation extends Model
{
    use HasTranslations;

    public const CACHE_TTL = 86400;

    protected $table = 'site_translations';

    protected $fillable = ['category', 'key', 'value', 'is_published'];

    public $translatable = ['value'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public static function cacheKey(string $category, string $key, string $locale): string
    {
        return "translator.{$category}.{$key}.{$locale}";
    }

    /**
     * Published translation for the locale, falling back to the application
     * fallback locale and then to any filled translation. Null when the
     * translation does not exist or is unpublished.
     */
    public static function get(string $category, string $key, ?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();

        return Cache::remember(
            static::cacheKey($category, $key, $locale),
            static::CACHE_TTL,
            function () use ($category, $key, $locale): ?string {
                $row = static::query()
                    ->where('category', $category)
                    ->where('key', $key)
                    ->where('is_published', true)
                    ->first();

                if ($row === null) {
                    return null;
                }

                $translations = $row->getTranslations('value');

                return $translations[$locale]
                    ?? $translations[config('app.fallback_locale')]
                    ?? (reset($translations) ?: null);
            },
        );
    }
}
