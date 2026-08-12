<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Office
 */
class OfficeResource extends JsonResource
{
    public static $wrap = null;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type->value,
            'name' => $this->name,
            'district' => $this->district,
            'address' => $this->address,
            'phone' => $this->phone,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'region' => $this->whenLoaded('region', fn (): ?array => $this->region === null ? null : [
                'slug' => $this->region->slug,
                'name' => $this->region->name,
            ]),
        ];
    }
}
