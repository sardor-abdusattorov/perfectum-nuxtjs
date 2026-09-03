<?php

declare(strict_types=1);

namespace App\Http\Resources\V1\Concerns;

trait TranslatesRepeaterRows
{
    private function translate(mixed $value): string
    {
        if (! is_array($value)) {
            return (string) $value;
        }

        return $value[app()->getLocale()]
            ?? $value[config('app.fallback_locale')]
            ?? (string) (collect($value)->first(fn ($item): bool => filled($item)) ?? '');
    }

    /**
     * @param  array<int, array<string, mixed>>|null  $rows
     * @param  array<int, string>  $fields
     * @return array<int, array<string, mixed>>
     */
    private function rows(?array $rows, array $fields): array
    {
        return collect($rows ?? [])
            ->map(function (array $row) use ($fields): array {
                foreach ($fields as $field) {
                    $row[$field] = $this->translate($row[$field] ?? null);
                }

                return $row;
            })
            ->all();
    }
}
