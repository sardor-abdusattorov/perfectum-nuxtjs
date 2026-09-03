<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\PageResource;
use App\Models\Page;
use App\Support\PreviewToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PageController
{
    public function __invoke(Request $request, Page $page): JsonResponse
    {
        $page->registerView($request);

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
