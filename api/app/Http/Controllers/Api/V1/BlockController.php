<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Enums\PageKey;
use Illuminate\Http\JsonResponse;

class BlockController
{
    public function __invoke(PageKey $page): JsonResponse
    {
        return response()->json([
            'data' => [
                'page' => $page->value,
                'blocks' => content_blocks($page),
            ],
        ]);
    }
}
