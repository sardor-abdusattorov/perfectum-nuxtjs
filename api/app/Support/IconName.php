<?php

declare(strict_types=1);

namespace App\Support;

use BladeUI\Icons\Exceptions\SvgNotFound;

class IconName
{
    /**
     * The picker stores a Blade Icons name and the site renders inline SVG, so
     * the markup is resolved here instead of asking the frontend to carry a
     * second icon library that has to stay in sync with this one. Iconify
     * names are accepted too — earlier records were seeded with them, and a
     * set that dropped an icon upstream falls through to the local brand set.
     *
     * @param  array<string, string>  $attributes
     */
    public static function svg(?string $name, array $attributes = []): ?string
    {
        foreach (static::candidates($name) as $candidate) {
            $svg = static::render($candidate, $attributes);

            if ($svg !== null) {
                return $svg;
            }
        }

        return null;
    }

    public static function blade(?string $name): ?string
    {
        foreach (static::candidates($name) as $candidate) {
            if (static::render($candidate) !== null) {
                return $candidate;
            }
        }

        return null;
    }

    public static function exists(?string $name): bool
    {
        return static::blade($name) !== null;
    }

    /**
     * @return array<int, string>
     */
    private static function candidates(?string $name): array
    {
        if (blank($name)) {
            return [];
        }

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
