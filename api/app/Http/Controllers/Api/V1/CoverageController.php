<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Models\CoverageLayer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class CoverageController
{
    /**
     * The map draws one layer at a time, so the list carries only what the
     * switch needs and each collection is fetched on demand.
     */
    public function index(): JsonResponse
    {
        $layers = self::available()
            ->ordered()
            ->get(['key', 'name', 'color', 'features'])
            ->map(fn (CoverageLayer $layer): array => [
                'key' => $layer->key,
                'name' => $layer->name,
                'color' => $layer->color,
                'features' => $layer->features,
                'url' => route('api.v1.coverage.show', $layer->key),
            ]);

        return response()->json(['data' => $layers]);
    }

    public function show(string $layer): JsonResponse
    {
        $found = self::available()->where('key', $layer)->firstOrFail();

        return response()->json($found->geojson);
    }

    private static function available(): Builder
    {
        return CoverageLayer::query()->published()->whereNotNull('geojson');
    }
}
