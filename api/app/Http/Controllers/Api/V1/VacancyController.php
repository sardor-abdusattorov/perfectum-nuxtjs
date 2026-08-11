<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\ListsRecords;
use App\Http\Resources\V1\VacancyResource;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VacancyController
{
    use ListsRecords;

    public function index(Request $request): ResourceCollection
    {
        $records = Vacancy::query()
            ->published()
            ->orderBy('sort');

        return VacancyResource::collection($this->paginate($records, $request, ['title']));
    }

    public function show(Vacancy $vacancy): JsonResponse
    {
        return response()->json(['data' => VacancyResource::make($vacancy)->resolve()]);
    }
}
