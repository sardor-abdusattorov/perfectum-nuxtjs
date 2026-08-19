<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Http\Resources\V1\Concerns\OmitsBodyFromLists;
use App\Models\Tender;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Tender
 */
class TenderResource extends JsonResource
{
    use OmitsBodyFromLists;

    public static $wrap = null;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'content' => $this->body($request, $this->content),
            'state' => $this->state->value,
            'deadline_at' => $this->deadline_at?->toDateString(),
            'published_at' => $this->published_at?->toDateString(),
            'files' => collect($this->files ?? [])
                ->map(fn (string $path): ?string => stored_url($path))
                ->filter()
                ->values()
                ->all(),
        ];
    }
}
