<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSettings extends Model
{
    public const CACHE_TTL = 86400;

    protected $table = 'site_settings';

    protected $fillable = ['name', 'value', 'is_published'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public static function cacheKey(): string
    {
        return 'site_settings.published';
    }

    /**
     * @return array<string, string|null>
     */
    public static function published(): array
    {
        return Cache::remember(
            static::cacheKey(),
            static::CACHE_TTL,
            fn (): array => static::query()
                ->where('is_published', true)
                ->pluck('value', 'name')
                ->all(),
        );
    }

    public static function get(string $name, mixed $default = null): mixed
    {
        return static::published()[$name] ?? $default;
    }

    public static function getEmbedUrl(string $name): ?string
    {
        $url = static::get($name);

        return blank($url) ? null : static::normalizeEmbedUrl($url);
    }

    public static function normalizeEmbedUrl(string $url): string
    {
        if (str_contains($url, '/embed/')) {
            return $url;
        }

        if (preg_match('/(?:youtu\.be\/|youtube\.com\/watch\?v=|v=)([^&\n?#]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/'.$matches[1];
        }

        return $url;
    }
}
