<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

/**
 * The old site's addresses still ride in ads and messengers, so every page
 * may name the paths it answers for. The site's server consults this map and
 * sends the visitor on with a 301.
 */
class RedirectController
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'data' => Cache::remember(
                Page::redirectsCacheKey(),
                Page::CACHE_TTL,
                fn (): array => Page::query()
                    ->published()
                    ->whereNotNull('redirect_from')
                    ->pluck('redirect_from', 'slug')
                    ->flatMap(fn (array $paths, string $slug): array => array_fill_keys($paths, "/pages/{$slug}"))
                    ->all(),
            ),
        ]);
    }
}
