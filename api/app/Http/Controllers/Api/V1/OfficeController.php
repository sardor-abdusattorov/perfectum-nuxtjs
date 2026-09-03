<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Enums\OfficeType;
use App\Http\Controllers\Api\V1\Concerns\ListsRecords;
use App\Http\Resources\V1\OfficeResource;
use App\Models\Office;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OfficeController
{
    use ListsRecords;

    public function __invoke(Request $request): JsonResponse
    {
        $offices = Office::query()
            ->published()
            ->with('region')
            ->forNetwork($this->network($request))
            ->ofType(OfficeType::tryFrom((string) $request->query('type', '')))
            ->inRegion($request->query('region'))
            ->ordered()
            ->get();

        return response()->json(['data' => OfficeResource::collection($offices)->resolve()]);
    }
}
