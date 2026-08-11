<?php

declare(strict_types=1);

namespace App\Filament\Resources\Concerns;

use App\Support\Slug;

trait GeneratesSlug
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->fillSlug($data);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->fillSlug($data);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function fillSlug(array $data): array
    {
        if (filled($data['slug'] ?? null)) {
            return $data;
        }

        $data['slug'] = Slug::make(
            static::getResource()::getModel(),
            $data['title'] ?? $data['name'] ?? null,
            $this->record?->getKey(),
            $this->slugScope($data),
        );

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function slugScope(array $data): array
    {
        return [];
    }
}
