<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
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

    public static function cacheKey(string $locale): string
    {
        return "translations.{$locale}";
    }

    /**
     * @return array<string, string>
     */
    public static function published(?string $locale = null): array
    {
        $locale ??= app()->getLocale();

        return Cache::remember(
            static::cacheKey($locale),
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
                            $carry[$row->category.'.'.$row->key] = $value;
                        }

                        return $carry;
                    }, []);
            },
        );
    }

    /**
     * @return array<string, string>
     */
    public static function flat(?string $locale = null): array
    {
        $flat = [];

        foreach (static::published($locale) as $path => $value) {
            $flat[Str::after($path, '.')] = $value;
        }

        return $flat;
    }

    public static function get(string $category, string $key, ?string $locale = null): ?string
    {
        return static::published($locale)["{$category}.{$key}"] ?? null;
    }
}
