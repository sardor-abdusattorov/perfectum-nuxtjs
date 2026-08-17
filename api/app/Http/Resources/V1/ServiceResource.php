<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Http\Resources\V1\Concerns\TranslatesRepeaterRows;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Service
 */
class ServiceResource extends JsonResource
{
    use TranslatesRepeaterRows;

    public static $wrap = null;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'excerpt' => $this->excerpt,
            'lead' => $this->lead,
            'content' => $this->content,
            'price' => $this->price,
            'icon' => $this->mediaUrl('icon'),
            'image' => $this->imageUrl(),
            'ussd' => $this->ussd,
            'facts' => $this->rows($this->facts, ['label', 'value']),
            'steps' => $this->rows($this->steps, ['text']),
            'is_featured' => $this->is_featured,
            'network' => $this->network?->value,
            'category' => $this->whenLoaded('category', fn (): ?array => $this->category === null ? null : [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'network' => $this->category->network?->value,
            ]),
        ];
    }
}
