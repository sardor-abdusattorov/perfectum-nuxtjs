<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\ListsRecords;
use App\Http\Resources\V1\CategoryResource;
use App\Models\Concerns\BelongsToNetwork;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController
{
    use ListsRecords;

    public function __invoke(Request $request, string $taxonomy): JsonResponse
    {
        $model = collect(taxonomies())->first(
            fn (string $class): bool => $class::make()->getTable() === str_replace('-', '_', $taxonomy)
        );

        if ($model === null) {
            throw new NotFoundHttpException;
        }

        $query = $model::query()->published()->ordered();

        if (in_array(BelongsToNetwork::class, class_uses_recursive($model), true)) {
            $query->forNetwork($this->network($request));
        }

        return response()->json([
            'data' => CategoryResource::collection($query->get())->resolve(),
        ]);
    }
}
