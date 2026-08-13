<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Action;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Action
 */
class ActionResource extends JsonResource
{
    public static $wrap = null;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'badge' => $this->badge,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'preview_image' => $this->mediaUrl('preview_image'),
            'main_image' => $this->mediaUrl('main_image'),
            'starts_at' => $this->starts_at?->toDateString(),
            'ends_at' => $this->ends_at?->toDateString(),
            'category' => $this->whenLoaded('category', fn (): ?array => $this->category === null ? null : [
                'slug' => $this->category->slug,
                'name' => $this->category->name,
            ]),
        ];
    }
}
