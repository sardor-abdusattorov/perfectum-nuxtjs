<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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

    public static function cacheKey(): string
    {
        return 'settings.all';
    }

    /**
     * @var array<string, mixed>|null
     */
    protected static ?array $values = null;

    /**
     * @return array<string, mixed>
     */
    public static function values(): array
    {
        return static::$values ??= Cache::remember(
            static::cacheKey(),
            static::CACHE_TTL,
            fn (): array => static::query()->get()->mapWithKeys(
                fn (self $row): array => [$row->key => $row->value]
            )->all(),
        );
    }

    public static function forgetValues(): void
    {
        static::$values = null;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return static::values()[$key] ?? $default;
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
        return stored_url(self::get('seo.og_image'));
    }

    /**
     * @return array{title: string, description: string, keywords: string, robots: string, og_image: ?string}
     */
    public static function seo(): array
    {
        $locale = app()->getLocale();
        $fallback = config('app.fallback_locale');

        $titles = self::get('seo.title', []);
        $descriptions = self::get('seo.description', []);
        $keywords = self::get('seo.keywords', []);

        return [
            'title' => $titles[$locale] ?? $titles[$fallback] ?? config('app.name'),
            'description' => $descriptions[$locale] ?? $descriptions[$fallback] ?? '',
            'keywords' => $keywords[$locale] ?? $keywords[$fallback] ?? '',
            'robots' => self::get('seo.indexing_enabled', true)
                ? 'index, follow'
                : 'noindex, nofollow',
            'og_image' => self::getOgImage(),
        ];
    }
}
