<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Settings extends Model
{
    public const CACHE_TTL = 86400;

    protected $table = 'settings';

    protected $fillable = ['key', 'value'];

    public function getValueAttribute(?string $value): mixed
    {
        if ($value === null) {
            return null;
        }

        $decoded = json_decode($value, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
    }

    public function setValueAttribute(mixed $value): void
    {
        $this->attributes['value'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public static function cacheKey(string $key): string
    {
        return "settings.{$key}";
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = Cache::remember(
            static::cacheKey($key),
            static::CACHE_TTL,
            fn (): mixed => static::query()->where('key', $key)->first()?->value,
        );

        return $value ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function getOgImage(): ?string
    {
        $path = self::get('seo.og_image');

        if (blank($path)) {
            return null;
        }

        return str_starts_with($path, 'http')
            ? $path
            : asset(Storage::url($path));
    }

    /**
     * @return array{title: string, description: string, keywords: string, robots: string, ogImage: ?string}
     */
    public static function seo(): array
    {
        $locale = app()->getLocale();

        $titles = self::get('seo.title', []);
        $descriptions = self::get('seo.description', []);
        $keywords = self::get('seo.keywords', []);

        return [
            'title' => $titles[$locale] ?? config('app.name'),
            'description' => $descriptions[$locale] ?? '',
            'keywords' => $keywords[$locale] ?? '',
            'robots' => self::get('seo.indexing_enabled', true)
                ? 'index, follow'
                : 'noindex, nofollow',
            'ogImage' => self::getOgImage(),
        ];
    }
}
