<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Models\CoverageLayer;
use App\Models\Region;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CoverageController
{
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

        return response()->json([
            'data' => $layers,
            'cities' => Region::query()
                ->published()
                ->located()
                ->ordered()
                ->get(['id', 'name', 'latitude', 'longitude'])
                ->map(fn (Region $region): array => [
                    'id' => $region->id,
                    'name' => $region->name,
                    'center' => [$region->latitude, $region->longitude],
                ]),
        ]);
    }

    public function show(Request $request, string $layer): JsonResponse
    {
        $found = self::available()->where('key', $layer)->firstOrFail();

        $response = new JsonResponse;
        $response->setEtag(hash('xxh128', (string) $found->getRawOriginal('geojson')));

        if ($response->isNotModified($request)) {
            return $response;
        }

        return $response->setData($found->featureCollection());
    }

    private static function available(): Builder
    {
        return CoverageLayer::query()->published()->whereNotNull('geojson');
    }
}
