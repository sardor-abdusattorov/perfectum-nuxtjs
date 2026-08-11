<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\ListsRecords;
use App\Http\Resources\V1\ActionResource;
use App\Models\Action;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function show(string $slug): JsonResponse
    {
        $record = Action::query()->published()->with('category')->where('slug', $slug)->first();

        if ($record === null) {
            throw new NotFoundHttpException;
        }

        return response()->json(['data' => ActionResource::make($record)->resolve()]);
    }
}
