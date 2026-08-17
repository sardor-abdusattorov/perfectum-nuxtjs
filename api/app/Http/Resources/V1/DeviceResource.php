<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Http\Resources\V1\Concerns\TranslatesRepeaterRows;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Device
 */
class DeviceResource extends JsonResource
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
            'brand' => $this->brand,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'specs' => $this->rows($this->specs, ['label', 'value']),
            'image' => $this->imageUrl(),
            'price' => $this->price,
            'in_stock' => $this->in_stock,
            'category' => $this->whenLoaded('category', fn (): ?array => $this->category === null ? null : [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'network' => $this->category->network?->value,
            ]),
        ];
    }
}
