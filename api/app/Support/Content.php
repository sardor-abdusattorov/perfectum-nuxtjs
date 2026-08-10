<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Models\ContentBlock;

class Content
{
    /**
     * Raw, per-locale data for an admin form.
     *
     * @return array<string, mixed>
     */
    public static function get(PageKey $page, ContentBlockKey $key): array
    {
        return ContentBlock::query()
            ->page($page)
            ->key($key)
            ->first()
            ?->getRawData() ?? [];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function save(PageKey $page, ContentBlockKey $key, array $data): ContentBlock
    {
        return ContentBlock::updateOrCreate(
            ['page' => $page, 'key' => $key],
            ['data' => $data],
        );
    }

    public static function delete(PageKey $page, ContentBlockKey $key): void
    {
        ContentBlock::query()->page($page)->key($key)->delete();
    }

    public static function exists(PageKey $page, ContentBlockKey $key): bool
    {
        return ContentBlock::query()->page($page)->key($key)->exists();
    }
}
