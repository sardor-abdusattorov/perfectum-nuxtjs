<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\PageResource;
use App\Models\Page;
use App\Support\PreviewToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class PageController
{
    public function __invoke(Page $page): JsonResponse
    {
        /**
         * A preview is one editor looking at one draft, and it changes with
         * every save — it reads past the cache and, more to the point, never
         * writes an unpublished page into it for the next visitor to be served.
         */
        if (PreviewToken::requested()) {
            return response()->json(['data' => PageResource::make($page)->resolve()]);
        }

        return response()->json([
            'data' => Cache::remember(
                Page::cacheKey($page->slug, app()->getLocale()),
                Page::CACHE_TTL,
                fn (): array => PageResource::make($page)->resolve(),
            ),
        ]);
    }
}
