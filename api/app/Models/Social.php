<?php

namespace App\Models;

use BladeUI\Icons\Exceptions\SvgNotFound;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    public const CACHE_TTL = 86400;

    /**
     * @var array<string, string|null>
     */
    private static array $names = [];

    /**
     * @var array<string, string|null>
     */
    private static array $icons = [];

    protected $table = 'socials';

    protected $fillable = [
        'name',
        'icon',
        'url',
        'sort',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function setIconAttribute(?string $value): void
    {
        $this->attributes['icon'] = static::iconName($value) ?? $value;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('id');
    }

    public static function cacheKey(): string
    {
        return 'socials.published';
    }

    public static function iconName(?string $name): ?string
    {
        if (blank($name)) {
            return null;
        }

        return static::$names[$name] ??= collect(static::candidates($name))
            ->first(fn (string $candidate): bool => static::renderIcon($candidate) !== null);
    }

    /**
     * @param  array<string, string>  $attributes
     */
    public static function iconSvg(?string $name, array $attributes = []): ?string
    {
        $icon = static::iconName($name);

        if ($icon === null) {
            return null;
        }

        return static::$icons[$icon.'|'.serialize($attributes)] ??= static::renderIcon($icon, $attributes);
    }

    public static function hasIcon(?string $name): bool
    {
        return static::iconName($name) !== null;
    }

    /**
     * @return array<int, string>
     */
    private static function candidates(string $name): array
    {
        $names = [$name];

        if (str_contains($name, ':')) {
            [$set, $icon] = explode(':', $name, 2);

            $names[] = match ($set) {
                'simple-icons' => 'si-'.$icon,
                'heroicons' => 'heroicon-o-'.$icon,
                default => $set.'-'.$icon,
            };

            $names[] = 'brand-'.$icon;
        }

        return array_values(array_unique($names));
    }

    /**
     * @param  array<string, string>  $attributes
     */
    private static function renderIcon(string $name, array $attributes = []): ?string
    {
        try {
            return svg($name, '', ['aria-hidden' => 'true', 'focusable' => 'false', ...$attributes])->toHtml();
        } catch (SvgNotFound) {
            return null;
        }
    }
}
