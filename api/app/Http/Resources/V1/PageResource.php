<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Page;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

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
            'content' => filled($this->content) ? $this->content : null,
            'image' => $this->imageUrl(),
            'is_group' => $this->is_group,
            'parent' => $this->parent?->only('slug', 'title'),
            'cards' => $this->children
                ->where('status', true)
                ->map(fn (Page $card): array => [
                    'slug' => $card->slug,
                    'title' => $card->title,
                    'text' => self::excerpt($card),
                ])
                ->values()
                ->all(),
            'seo' => [
                'title' => filled($this->meta_title) ? $this->meta_title : $this->title,
                'description' => filled($this->meta_description) ? $this->meta_description : $defaults['description'],
                'keywords' => $defaults['keywords'],
                'robots' => $defaults['robots'],
                'og_image' => $this->imageUrl() ?? $defaults['og_image'],
            ],
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }

    /**
     * The card carries the opening of the page under its heading, the way the
     * old site did — nothing to fill in by hand, and it stays in step with the
     * text it came from.
     */
    private static function excerpt(Page $card): string
    {
        $text = trim(html_entity_decode(strip_tags((string) $card->content)));

        return Str::limit(preg_replace('/\s+/u', ' ', $text) ?? '', 180);
    }
}
