<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\PageResource;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageController
{
    public function __invoke(string $slug): JsonResponse
    {
        $page = Page::findPublished($slug);

        if ($page === null) {
            throw new NotFoundHttpException;
        }

        return PageResource::make($page)->response();
    }
}
