<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Unlike the other feeds, the news list carries the whole body: the mobile app
 * shows a story straight from the list it already downloaded, and asking it to
 * fetch every item again to read one was the wrong trade.
 *
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
            'preview_image' => $this->mediaUrl('preview_image'),
            'main_image' => $this->mediaUrl('main_image'),
            'is_featured' => $this->is_featured,
            'published_at' => $this->published_at?->toDateString(),
            'category' => $this->whenLoaded('category', fn (): ?array => $this->category === null
                ? null
                : CategoryResource::make($this->category)->resolve()),
        ];
    }
}
