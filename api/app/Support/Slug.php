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

    public static function base(string $text): string
    {
        $slug = trim(mb_substr(Str::slug($text), 0, static::MAX_LENGTH), '-');

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

        $order = array_unique(array_merge(['en'], app_locales()));

        foreach ($order as $locale) {
            if (filled($source[$locale] ?? null)) {
                return strip_tags((string) $source[$locale]);
            }
        }

        return '';
    }
}
