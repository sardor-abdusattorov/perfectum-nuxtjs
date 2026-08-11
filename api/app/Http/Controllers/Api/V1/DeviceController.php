<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\ListsRecords;
use App\Http\Resources\V1\DeviceResource;
use App\Models\Device;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeviceController
{
    use ListsRecords;

    public function index(Request $request): ResourceCollection
    {
        $records = Device::query()
            ->published()
            ->with('category')
            ->forNetwork($this->network($request))
            ->inCategory($request->query('category'))
            ->orderBy('sort');

        return DeviceResource::collection($this->paginate($records, $request, ['name', 'brand']));
    }

    public function show(string $slug): JsonResponse
    {
        $record = Device::query()->published()->with('category')->where('slug', $slug)->first();

        if ($record === null) {
            throw new NotFoundHttpException;
        }

        return response()->json(['data' => DeviceResource::make($record)->resolve()]);
    }
}
