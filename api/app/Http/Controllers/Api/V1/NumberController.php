<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Services\Bss\NumberService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class NumberController
{
    private const CODE = '80';

    public function __invoke(Request $request, NumberService $service): JsonResponse
    {
        $validated = $request->validate([
            'sku' => ['nullable', 'string', 'max:10'],
            'page' => ['nullable', 'integer', 'min:1'],
            'size' => ['nullable', 'integer', 'min:1', 'max:48'],
            'mask' => ['nullable', 'string', 'regex:/^'.self::CODE.'[\d*]{7}$/'],
        ]);

        $mask = $validated['mask'] ?? null;

        try {
            return response()->json(['data' => $service->search(
                (string) ($validated['sku'] ?? ''),
                $mask === self::CODE.str_repeat('*', 7) ? null : $mask,
                max(1, (int) ($validated['page'] ?? 1)),
                max(1, min(48, (int) ($validated['size'] ?? 28))),
                $request->ip(),
            )]);
        } catch (Throwable $exception) {
            Log::error('BSS numbers fetch failed: '.$exception->getMessage());

            return response()->json(['message' => 'bss_unavailable'], 502);
        }
    }
}
