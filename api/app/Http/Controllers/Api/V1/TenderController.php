<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\ListsRecords;
use App\Http\Resources\V1\TenderResource;
use App\Models\Tender;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TenderController
{
    use ListsRecords;

    public function index(Request $request): ResourceCollection
    {
        $records = Tender::query()
            ->published()
            ->orderByDesc('published_at')
            ->orderByDesc('deadline_at');

        return TenderResource::collection($this->paginate($records, $request, ['title']));
    }

    public function show(Request $request, Tender $tender): JsonResponse
    {
        $tender->registerView($request);

        return response()->json(['data' => TenderResource::make($tender)->resolve()]);
    }
}
