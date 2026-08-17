<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Http\Resources\V1\Concerns\TranslatesRepeaterRows;
use App\Models\Tariff;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Tariff
 */
class TariffResource extends JsonResource
{
    use TranslatesRepeaterRows;

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

    /**
     * @return array<int, array<string, mixed>>
     */
    private function buttonRows(): array
    {
        return collect($this->rows($this->buttons, ['name']))
            ->map(fn (array $button): array => [
                ...$button,
                'icon' => stored_url($button['icon'] ?? null),
            ])
            ->all();
    }
}
