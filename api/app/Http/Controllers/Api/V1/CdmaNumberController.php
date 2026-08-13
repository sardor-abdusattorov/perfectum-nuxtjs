<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Models\FreeNumberFilter;
use App\Services\Cdma\FreeNumberService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class CdmaNumberController
{
    public function filters(FreeNumberService $service): JsonResponse
    {
        $prefixes = FreeNumberFilter::query()
            ->published()
            ->ofType(FreeNumberFilter::TYPE_PREFIX)
            ->orderBy('value')
            ->get(['name', 'value'])
            ->map(fn (FreeNumberFilter $filter): array => [
                'name' => $filter->name,
                'value' => $filter->value,
            ]);

        try {
            $prices = $service->prices();
        } catch (Throwable $exception) {
            Log::warning('CDMA prices fetch failed: '.$exception->getMessage());

            $prices = FreeNumberFilter::query()
                ->published()
                ->ofType(FreeNumberFilter::TYPE_PRICE)
                ->get(['value'])
                ->map(fn (FreeNumberFilter $filter): array => [
                    'code' => (int) $filter->value,
                    'price' => (int) $filter->value,
                ])
                ->sortBy('price')
                ->values();
        }

        return response()->json(['data' => [
            'prefixes' => $prefixes,
            'prices' => $prices,
        ]]);
    }

    public function search(Request $request, FreeNumberService $service): JsonResponse
    {
        $validated = $request->validate([
            'number' => ['required', 'regex:/^\d{1,4}$/'],
            'prefix' => ['nullable', 'regex:/^\d{1,3}$/'],
            'price' => ['nullable', 'integer', 'min:-1'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        try {
            return response()->json(['data' => $service->search(
                (string) $validated['number'],
                (string) ($validated['prefix'] ?? '0'),
                (int) ($validated['price'] ?? -1),
                max(1, (int) ($validated['page'] ?? 1)),
            )]);
        } catch (Throwable $exception) {
            Log::error('CDMA numbers fetch failed: '.$exception->getMessage());

            return response()->json(['message' => 'cdma_unavailable'], 502);
        }
    }
}
