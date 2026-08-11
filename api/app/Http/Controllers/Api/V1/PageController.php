<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\PageResource;
use App\Models\Page;
use Illuminate\Http\JsonResponse;

class PageController
{
    public function __invoke(Page $page): JsonResponse
    {
        return PageResource::make($page)->response();
    }
}
