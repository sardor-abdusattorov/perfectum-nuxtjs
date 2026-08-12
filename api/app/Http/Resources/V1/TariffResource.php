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
            'lead' => $this->lead,
            'terms' => $this->terms,
            'features' => $this->features ?? [],
            'image' => $this->imageUrl(),
            'modal_image' => $this->modalImageUrl(),
            'ussd' => $this->ussd,
            'buttons' => $this->buttonsPayload(),
            'is_featured' => $this->is_featured,
            'is_archived' => $this->is_archived,
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
     * @return array<int, array<string, mixed>>
     */
    private function buttonsPayload(): array
    {
        return collect($this->buttons ?? [])
            ->map(fn (array $button): array => [
                ...$button,
                'name' => $this->translate($button['name'] ?? null),
                'icon' => blank($button['icon'] ?? null)
                    ? null
                    : Storage::disk('public')->url($button['icon']),
            ])
            ->all();
    }
}
