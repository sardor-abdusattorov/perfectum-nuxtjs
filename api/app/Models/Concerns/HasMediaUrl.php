<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;

trait HasMediaUrl
{
    public function mediaUrl(string $attribute = 'image'): ?string
    {
        $path = $this->getAttribute($attribute);

        if (blank($path)) {
            return null;
        }

        return str_starts_with($path, 'http')
            ? $path
            : Storage::disk('public')->url($path);
    }

    public function imageUrl(): ?string
    {
        return $this->mediaUrl();
    }
}
