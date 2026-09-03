<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\ListsRecords;
use App\Http\Resources\V1\ServiceResource;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceController
{
    use ListsRecords;

    public function index(Request $request): ResourceCollection
    {
        $records = Service::query()
            ->published()
            ->with('category')
            ->forNetwork($this->network($request))
            ->inCategory($request->query('category'))
            ->orderBy('sort')
            ->orderBy('id');

        return ServiceResource::collection($this->paginate($records, $request, ['name', 'excerpt']));
    }

    public function show(Request $request, Service $service): JsonResponse
    {
        $service->registerView($request);

        return response()->json(['data' => ServiceResource::make($service->loadMissing('category'))->resolve()]);
    }
}
