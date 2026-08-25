<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Model
 */
class CategoryResource extends JsonResource
{
    public static $wrap = null;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $attributes = $this->resource->getAttributes();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => array_key_exists('slug', $attributes) ? $this->slug : null,
            'network' => array_key_exists('network', $attributes) ? $this->network?->value : null,
            'in_catalog' => array_key_exists('in_catalog', $attributes) ? (bool) $this->in_catalog : null,
        ];
    }
}
