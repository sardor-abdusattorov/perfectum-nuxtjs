<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Faq
 */
class FaqResource extends JsonResource
{
    public static $wrap = null;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'question' => $this->question,
            'answer' => $this->answer,
            'category' => $this->whenLoaded('category', fn (): ?array => $this->category === null ? null : [
                'slug' => $this->category->slug,
                'name' => $this->category->name,
            ]),
        ];
    }
}
