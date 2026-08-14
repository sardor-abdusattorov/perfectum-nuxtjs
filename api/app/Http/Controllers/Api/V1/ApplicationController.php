<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\StoreApplicationRequest;
use App\Models\Application;
use Illuminate\Http\JsonResponse;

class ApplicationController
{
    public function __invoke(StoreApplicationRequest $request): JsonResponse
    {
        Application::create([
            'phone' => $request->formattedPhone(),
            'theme_id' => $request->validated('theme'),
            'message' => $request->validated('message'),
            'status' => Application::STATUS_NEW,
            'ip_address' => $request->ip(),
        ]);

        return response()->json(['data' => ['submitted' => true]], 201);
    }
}
