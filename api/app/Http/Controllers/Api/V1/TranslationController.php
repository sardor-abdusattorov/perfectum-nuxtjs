<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Models\SiteTranslation;
use Illuminate\Http\JsonResponse;

class TranslationController
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'data' => SiteTranslation::grouped(),
        ]);
    }
}
