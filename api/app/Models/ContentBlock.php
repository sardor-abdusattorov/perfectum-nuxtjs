<?php

namespace App\Models;

use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ContentBlock extends Model
{
    public const CACHE_TTL = 86400;

    protected $fillable = [
        'page',
        'key',
        'data',
    ];

    protected $casts = [
        'page' => PageKey::class,
        'key' => ContentBlockKey::class,
    ];

    /**
     * Reading resolves every translatable node down to the current locale, so
     * the site never deals with per-locale arrays. The admin needs the raw
     * shape instead and calls getRawData().
     */
    protected function data(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): array => $value
                ? static::resolveLocale(json_decode($value, true) ?? [], app()->getLocale())
                : [],
            set: fn (mixed $value): string => json_encode($value, JSON_UNESCAPED_UNICODE),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getRawData(): array
    {
        return json_decode($this->attributes['data'] ?? '[]', true) ?? [];
    }

    public static function cacheKey(PageKey $page, string $locale): string
    {
        return "content_blocks.{$page->value}.{$locale}";
    }

    public function scopePage(Builder $query, PageKey $page): Builder
    {
        return $query->where('page', $page);
    }

    public function scopeKey(Builder $query, ContentBlockKey $key): Builder
    {
        return $query->where('key', $key);
    }

    protected static function resolveLocale(mixed $value, string $locale): mixed
    {
        if (! is_array($value)) {
            return $value;
        }

        if (static::isTranslatable($value)) {
            return $value[$locale]
                ?? $value[config('app.fallback_locale')]
                ?? reset($value);
        }

        return array_map(
            fn ($item) => static::resolveLocale($item, $locale),
            $value,
        );
    }

    /**
     * A node is a translation set when every one of its keys is a locale code,
     * which lets any depth of the JSON carry translations without a schema.
     *
     * @param  array<mixed>  $value
     */
    protected static function isTranslatable(array $value): bool
    {
        if ($value === [] || array_is_list($value)) {
            return false;
        }

        $locales = array_flip(config('app.locales', [config('app.locale')]));

        return array_diff_key($value, $locales) === [];
    }
}
