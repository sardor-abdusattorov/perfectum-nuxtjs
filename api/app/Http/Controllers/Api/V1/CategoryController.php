<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Enums\CategoryType;
use App\Http\Controllers\Api\V1\Concerns\ListsRecords;
use App\Http\Resources\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController
{
    use ListsRecords;

    public function __invoke(Request $request, CategoryType $type): JsonResponse
    {
        $categories = Category::query()
            ->published()
            ->type($type)
            ->forNetwork($this->network($request))
            ->orderBy('sort')
            ->get();

        return response()->json([
            'data' => CategoryResource::collection($categories)->resolve(),
        ]);
    }
}
