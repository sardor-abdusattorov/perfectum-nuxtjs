<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Tariff;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin Tariff
 */
class TariffResource extends JsonResource
{
    public static $wrap = null;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'price' => $this->price,
            'price_currency' => $this->price_currency,
            'price_period' => $this->price_period,
            'features' => $this->rows($this->features, ['title', 'note']),
            'descriptions' => $this->rows($this->descriptions, ['name', 'content']),
            'image' => $this->imageUrl(),
            'modal_image' => $this->modalImageUrl(),
            'buttons' => $this->buttonRows(),
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'type' => CategoryResource::make($this->whenLoaded('type')),
        ];
    }

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
     * Repeater rows keep one value per locale, so every listed field is
     * resolved down to the requested one before the row leaves the API.
     *
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

    /**
     * @return array<int, array<string, mixed>>
     */
    private function buttonRows(): array
    {
        return collect($this->rows($this->buttons, ['name']))
            ->map(fn (array $button): array => [
                ...$button,
                'icon' => blank($button['icon'] ?? null)
                    ? null
                    : Storage::disk('public')->url($button['icon']),
            ])
            ->all();
    }
}
