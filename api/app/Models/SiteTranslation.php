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

    public static function collectionCacheKey(string $locale): string
    {
        return "translations.{$locale}";
    }

    /**
     * @return array<string, string>
     */
    public static function flat(?string $locale = null): array
    {
        $locale ??= app()->getLocale();

        return Cache::remember(
            static::collectionCacheKey($locale),
            static::CACHE_TTL,
            function () use ($locale): array {
                $fallback = config('app.fallback_locale');

                return static::query()
                    ->where('is_published', true)
                    ->get()
                    ->reduce(function (array $carry, self $row) use ($locale, $fallback): array {
                        $translations = $row->getTranslations('value');
                        $value = $translations[$locale]
                            ?? $translations[$fallback]
                            ?? (reset($translations) ?: null);

                        if ($value !== null) {
                            $carry[$row->key] = $value;
                        }

                        return $carry;
                    }, []);
            },
        );
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
