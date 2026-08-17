<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\ListsRecords;
use App\Http\Resources\V1\NewsResource;
use App\Models\News;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NewsController
{
    use ListsRecords;

    public function index(Request $request): ResourceCollection
    {
        $news = News::query()
            ->published()
            ->with('category')
            ->forNetwork($this->network($request))
            ->inCategory($request->query('category'))
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at');

        return NewsResource::collection($this->paginate($news, $request, ['title']));
    }

    public function show(News $news): JsonResponse
    {
        return response()->json(['data' => NewsResource::make($news->loadMissing('category'))->resolve()]);
    }
}
