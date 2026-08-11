<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\ListsRecords;
use App\Http\Resources\V1\ActionResource;
use App\Models\Action;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ActionController
{
    use ListsRecords;

    public function index(Request $request): ResourceCollection
    {
        $records = Action::query()
            ->published()
            ->with('category')
            ->forNetwork($this->network($request))
            ->inCategory($request->query('category'))
            ->orderByDesc('ends_at');

        return ActionResource::collection($this->paginate($records, $request, ['title']));
    }

    public function show(Action $action): JsonResponse
    {
        return response()->json(['data' => ActionResource::make($action->loadMissing('category'))->resolve()]);
    }
}
