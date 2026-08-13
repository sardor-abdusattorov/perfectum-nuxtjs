<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\ListsRecords;
use App\Http\Resources\V1\FaqResource;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FaqController
{
    use ListsRecords;

    public function __invoke(Request $request): JsonResponse
    {
        $faqs = Faq::query()
            ->published()
            ->with('category')
            ->onPage($request->query('page'))
            ->inCategory($request->query('category'))
            ->orderBy('sort')
            ->get();

        return response()->json([
            'data' => FaqResource::collection($faqs)->resolve(),
        ]);
    }
}
