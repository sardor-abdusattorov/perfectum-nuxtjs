<?php

declare(strict_types=1);

namespace App\Filament\Support;

use Closure;
use Filament\Forms\Components\Field;
use Illuminate\Support\Str;

class Translated
{
    public static function itemLabel(string $field): Closure
    {
        return function (array $state) use ($field): ?string {
            $value = $state[$field] ?? null;

            if (is_array($value)) {
                $value = collect($value)->first(fn ($item): bool => filled($item));
            }

            $label = trim(strip_tags((string) $value));

            return $label === '' ? null : $label;
        };
    }

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
