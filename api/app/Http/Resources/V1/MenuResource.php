<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Menu
 */
class MenuResource extends JsonResource
{
    public static $wrap = null;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'target' => $this->open_in_new_tab ? '_blank' : null,
            'children' => $this->relationLoaded('children')
                ? self::collection($this->children)->resolve()
                : [],
        ];
    }
}
