<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\ListsRecords;
use App\Http\Resources\V1\TariffResource;
use App\Models\Tariff;
use App\Models\TariffFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

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
            ->orderBy('sort');

        return TariffResource::collection($this->paginate($records, $request, ['name']));
    }

    public function files(): JsonResponse
    {
        $files = TariffFile::query()
            ->published()
            ->ordered()
            ->get()
            ->map(fn (TariffFile $file): array => [
                'name' => $file->name,
                'url' => Storage::disk('public')->url($file->file),
            ]);

        return response()->json(['data' => $files]);
    }

    public function show(Tariff $tariff): JsonResponse
    {
        return response()->json([
            'data' => TariffResource::make($tariff->loadMissing(['category', 'type']))->resolve(),
        ]);
    }
}
