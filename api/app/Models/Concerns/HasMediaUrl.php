<?php

declare(strict_types=1);

namespace App\Models\Concerns;

trait HasMediaUrl
{
    public function mediaUrl(string $attribute = 'image'): ?string
    {
        return stored_url($this->getAttribute($attribute));
    }

    public function imageUrl(): ?string
    {
        return $this->mediaUrl();
    }
}
