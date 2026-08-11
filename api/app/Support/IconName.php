<?php

declare(strict_types=1);

namespace App\Support;

use BladeUI\Icons\Exceptions\SvgNotFound;

class IconName
{
    /** @var array<string, string|null> */
    private static array $resolved = [];

    /** @var array<string, string|null> */
    private static array $rendered = [];

    /**
     * @param  array<string, string>  $attributes
     */
    public static function svg(?string $name, array $attributes = []): ?string
    {
        $blade = static::blade($name);

        if ($blade === null) {
            return null;
        }

        $key = $blade.'|'.serialize($attributes);

        return static::$rendered[$key] ??= static::render($blade, $attributes);
    }

    public static function blade(?string $name): ?string
    {
        if (blank($name)) {
            return null;
        }

        return static::$resolved[$name] ??= static::resolve($name);
    }

    public static function exists(?string $name): bool
    {
        return static::blade($name) !== null;
    }

    private static function resolve(string $name): ?string
    {
        foreach (static::candidates($name) as $candidate) {
            if (static::render($candidate) !== null) {
                return $candidate;
            }
        }

        return null;
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
    private static function render(string $name, array $attributes = []): ?string
    {
        try {
            return svg($name, '', [
                'aria-hidden' => 'true',
                'focusable' => 'false',
                ...$attributes,
            ])->toHtml();
        } catch (SvgNotFound) {
            return null;
        }
    }
}
