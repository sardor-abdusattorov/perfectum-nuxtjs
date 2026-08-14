<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Models\CoverageLayer;
use Illuminate\Http\JsonResponse;

class CoverageController
{
    public function __invoke(): JsonResponse
    {
        $layers = CoverageLayer::query()
            ->published()
            ->whereNotNull('geojson')
            ->ordered()
            ->get()
            ->map(fn (CoverageLayer $layer): array => [
                'key' => $layer->key,
                'name' => $layer->name,
                'color' => $layer->color,
                'geojson' => $layer->geojson,
            ]);

        return response()->json(['data' => $layers]);
    }
}
