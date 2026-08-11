<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Tender
 */
class TenderResource extends JsonResource
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
            'content' => $this->content,
            'state' => $this->state->value,
            'deadline_at' => $this->deadline_at?->toDateString(),
            'files' => collect($this->files ?? [])
                ->map(fn (string $path): string => Storage::disk('public')->url($path))
                ->all(),
        ];
    }
}
