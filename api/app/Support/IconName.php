<?php

declare(strict_types=1);

namespace App\Support;

class IconName
{
    /**
     * Blade Icons names are what the admin picker stores, Iconify names are
     * what the site renders. Both address the same sets, only the prefix
     * differs.
     */
    public static function toIconify(?string $name): ?string
    {
        if (blank($name)) {
            return null;
        }

        if (str_contains($name, ':')) {
            return $name;
        }

        if (str_starts_with($name, 'si-')) {
            return 'simple-icons:'.substr($name, 3);
        }

        if (preg_match('/^heroicon-([osm])-(.+)$/', $name, $matches) === 1) {
            return 'heroicons:'.$matches[2].match ($matches[1]) {
                'o' => '',
                's' => '-solid',
                'm' => '-16-solid',
            };
        }

        return $name;
    }
}
