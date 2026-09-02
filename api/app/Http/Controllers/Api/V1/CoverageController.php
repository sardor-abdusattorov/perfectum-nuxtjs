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

    /**
     * The validator hashes the stored column rather than the rendered body:
     * both answer «not modified» for the same layer, but the column is a
     * string the query already carries, while the body costs a decode of six
     * megabytes of geometry, a rebuild and an encode — as much as sending it.
     * A timestamp would be cheaper still and wrong: two saves within the same
     * second share one, and the reader would keep the layer it has.
     */
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
