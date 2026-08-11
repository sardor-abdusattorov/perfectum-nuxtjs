<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\ListsRecords;
use App\Http\Resources\V1\TenderResource;
use App\Models\Tender;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TenderController
{
    use ListsRecords;

    public function index(Request $request): ResourceCollection
    {
        $records = Tender::query()
            ->published()
            ->orderByDesc('deadline_at');

        return TenderResource::collection($this->paginate($records, $request, ['title']));
    }

    public function show(string $slug): JsonResponse
    {
        $record = Tender::query()->published()->where('slug', $slug)->first();

        if ($record === null) {
            throw new NotFoundHttpException;
        }

        return response()->json(['data' => TenderResource::make($record)->resolve()]);
    }
}
