<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Social;
use App\Support\IconName;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Social
 */
class SocialResource extends JsonResource
{
    public static $wrap = null;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'svg' => IconName::svg($this->icon),
            'url' => $this->url,
        ];
    }
}
