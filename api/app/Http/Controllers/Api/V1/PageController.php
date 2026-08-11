<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Enums\PageKey;
use App\Models\Settings;
use Illuminate\Http\JsonResponse;

class PageController
{
    public function __invoke(PageKey $page): JsonResponse
    {
        return response()->json([
            'data' => [
                'page' => $page->value,
                'seo' => Settings::seo(),
                'blocks' => content_blocks($page),
            ],
        ]);
    }
}
