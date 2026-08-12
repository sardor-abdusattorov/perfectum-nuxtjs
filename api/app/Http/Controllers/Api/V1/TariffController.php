<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\ListsRecords;
use App\Http\Resources\V1\TariffResource;
use App\Models\Tariff;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TariffController
{
    use ListsRecords;

    public function index(Request $request): ResourceCollection
    {
        $records = Tariff::query()
            ->published()
            ->with(['category', 'type'])
            ->forNetwork($this->network($request))
            ->inCategory($request->query('category'))
            ->inCategory($request->query('type'), 'type')
            ->when(
                $request->boolean('archived'),
                fn ($query) => $query->archived(),
                fn ($query) => $query->current(),
            )
            ->orderBy('sort');

        return TariffResource::collection($this->paginate($records, $request, ['name']));
    }

    public function show(Tariff $tariff): JsonResponse
    {
        return response()->json([
            'data' => TariffResource::make($tariff->loadMissing(['category', 'type']))->resolve(),
        ]);
    }
}
