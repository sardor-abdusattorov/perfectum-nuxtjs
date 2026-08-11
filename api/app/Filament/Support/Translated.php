<?php

declare(strict_types=1);

namespace App\Filament\Support;

use Closure;
use Filament\Forms\Components\Field;
use Illuminate\Support\Str;

class Translated
{
    /**
     * Required only in the locales the site must not go without. Everything
     * reading translated content falls back to a filled locale, so demanding
     * every language just blocks publishing.
     */
    public static function required(): Closure
    {
        return fn (Field $component): bool => in_array(
            Str::afterLast($component->getStatePath(), '.'),
            static::locales(),
            true,
        );
    }

    /**
     * @return array<int, string>
     */
    public static function locales(): array
    {
        return (array) config('app.required_locales', [config('app.locale')]);
    }
}
