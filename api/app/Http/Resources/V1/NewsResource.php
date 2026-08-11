<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin News
 */
class NewsResource extends JsonResource
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
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'image' => $this->imageUrl(),
            'is_featured' => $this->is_featured,
            'published_at' => $this->published_at?->toDateString(),
            'category' => $this->whenLoaded('category', fn (): ?array => $this->category === null ? null : [
                'slug' => $this->category->slug,
                'name' => $this->category->name,
            ]),
        ];
    }
}
