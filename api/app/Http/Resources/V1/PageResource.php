<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Page;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Page
 */
class PageResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $defaults = Settings::seo();

        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'content' => $this->content,
            'image' => $this->imageUrl(),
            'seo' => [
                'title' => filled($this->meta_title) ? $this->meta_title : $this->title,
                'description' => filled($this->meta_description) ? $this->meta_description : $defaults['description'],
                'keywords' => filled($this->meta_keywords) ? $this->meta_keywords : $defaults['keywords'],
                'robots' => $defaults['robots'],
                'og_image' => $this->imageUrl() ?? $defaults['og_image'],
            ],
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
