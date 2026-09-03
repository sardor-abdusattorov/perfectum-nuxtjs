<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

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
