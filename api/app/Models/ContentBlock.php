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
                ? static::renderIcons(static::resolveLocale(json_decode($value, true) ?? [], app()->getLocale()))
                : [],
            set: fn (mixed $value): string => json_encode($value, JSON_UNESCAPED_UNICODE),
        );
    }

    /**
     * A node whose `icon` names one from the panel's set gets an `icon_svg`
     * sibling with the rendered mark, the way the socials payload carries
     * theirs — the site only ever prints markup, never resolves names.
     *
     * @param  array<array-key, mixed>  $value
     * @return array<array-key, mixed>
     */
    protected static function renderIcons(array $value): array
    {
        if (is_string($value['icon'] ?? null) && Social::hasIcon($value['icon'])) {
            $value['icon_svg'] = Social::iconSvg($value['icon']);
        }

        return array_map(
            fn ($item) => is_array($item) ? static::renderIcons($item) : $item,
            $value,
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

    /**
     * @return array<string, mixed>
     */
    public static function read(PageKey $page, ContentBlockKey $key): array
    {
        return static::query()->page($page)->key($key)->first()?->getRawData() ?? [];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function write(PageKey $page, ContentBlockKey $key, array $data): self
    {
        return static::updateOrCreate(['page' => $page, 'key' => $key], ['data' => $data]);
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

        $locales = array_flip(app_locales());

        return array_diff_key($value, $locales) === [];
    }
}
