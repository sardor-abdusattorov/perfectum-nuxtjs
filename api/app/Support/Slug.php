<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Slug
{
    public const MAX_LENGTH = 96;

    /**
     * @param  class-string<Model>  $model
     * @param  array<string, string|null>|string|null  $source
     * @param  array<string, mixed>  $scope
     */
    public static function make(string $model, mixed $source, mixed $ignoreKey = null, array $scope = []): string
    {
        $base = static::base(static::text($source));

        $taken = $model::query()
            ->when($ignoreKey !== null, fn ($query) => $query->whereKeyNot($ignoreKey))
            ->where($scope)
            ->where(fn ($query) => $query
                ->where('slug', $base)
                ->orWhere('slug', 'like', $base.'-%'))
            ->pluck('slug')
            ->all();

        if (! in_array($base, $taken, true)) {
            return $base;
        }

        $suffix = 2;

        while (in_array($base.'-'.$suffix, $taken, true)) {
            $suffix++;
        }

        return $base.'-'.$suffix;
    }

    public static function fromInput(?string $value): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return '';
        }

        $value = (string) preg_replace('#^[a-z][a-z0-9+.-]*://[^/]+#i', '', $value);
        $value = (string) strtok($value, '#');

        [$path, $query] = array_pad(explode('?', $value, 2), 2, null);

        if (filled($query)) {
            parse_str((string) $query, $params);

            $filter = collect($params)
                ->filter(fn (mixed $item): bool => is_string($item) && filled($item))
                ->last();

            if (filled($filter)) {
                return static::base((string) $filter);
            }
        }

        $segments = array_values(array_filter(
            explode('/', (string) $path),
            fn (string $segment): bool => trim($segment) !== '',
        ));

        $last = end($segments);

        return $last === false ? '' : static::base($last);
    }

    public static function base(string $text): string
    {
        $slug = trim(mb_substr(Str::slug($text, '-', 'ru'), 0, static::MAX_LENGTH), '-');

        return $slug === '' ? 'record-'.Str::lower(Str::random(6)) : $slug;
    }

    /**
     * @param  array<string, string|null>|string|null  $source
     */
    public static function text(mixed $source): string
    {
        if (is_string($source)) {
            return strip_tags($source);
        }

        if (! is_array($source)) {
            return '';
        }

        foreach (app_locales() as $locale) {
            if (filled($source[$locale] ?? null)) {
                return strip_tags((string) $source[$locale]);
            }
        }

        return '';
    }
}
