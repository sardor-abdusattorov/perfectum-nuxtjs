<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Models\Settings;
use Illuminate\Http\JsonResponse;

class MetricsController
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'data' => array_filter([
                'yandex' => Settings::get('metrics.yandex'),
                'google' => Settings::get('metrics.google'),
            ], fn (mixed $value): bool => filled($value)),
        ]);
    }
}
