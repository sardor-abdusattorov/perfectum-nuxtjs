<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\SocialResource;
use App\Models\Social;
use Illuminate\Http\JsonResponse;

class SocialController
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'data' => SocialResource::collection(Social::published())->resolve(),
        ]);
    }
}
