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

    /**
     * `page` here names a section of the site, not a page number; a JSON body
     * that sends a number where the query string sent a word is asking for
     * nothing in particular and gets the whole list.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $page = $request->input('page');

        $faqs = Faq::query()
            ->published()
            ->with('category')
            ->onPage(is_string($page) ? $page : null)
            ->inCategory($request->input('category'))
            ->orderBy('sort')
            ->get();

        return response()->json([
            'data' => FaqResource::collection($faqs)->resolve(),
        ]);
    }
}
