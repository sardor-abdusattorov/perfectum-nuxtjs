<?php

declare(strict_types=1);

namespace App\Support;

use BladeUI\Icons\Exceptions\SvgNotFound;

class IconName
{
    /**
     * The picker stores a Blade Icons name and the site renders inline SVG, so
     * the markup is resolved here instead of asking the frontend to carry a
     * second icon library that has to stay in sync with this one.
     */
    public static function svg(?string $name): ?string
    {
        if (blank($name)) {
            return null;
        }

        try {
            return svg($name, '', ['aria-hidden' => 'true', 'focusable' => 'false'])->toHtml();
        } catch (SvgNotFound) {
            return null;
        }
    }

    public static function exists(?string $name): bool
    {
        return static::svg($name) !== null;
    }
}
