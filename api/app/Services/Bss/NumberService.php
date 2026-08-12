<?php

declare(strict_types=1);

namespace App\Services\Bss;

use Illuminate\Support\Facades\Cache;

class NumberService
{
    private const CACHE_TTL = 30;

    public function __construct(private readonly BssClient $bss) {}

    /**
     * @return array<string, mixed>
     */
    public function search(string $sku, ?string $mask, int $page, int $size, ?string $clientIp = null): array
    {
        $priceBySku = collect($this->categories($clientIp))
            ->mapWithKeys(fn (array $category): array => [
                (string) data_get($category, 'sku') => (int) data_get($category, 'price', 0),
            ]);

        $response = $this->numbers($sku, $mask, $page, $size, $clientIp);

        return [
            'categories' => $priceBySku
                ->map(fn (int $price, string $categorySku): array => ['sku' => $categorySku, 'price' => $price])
                ->values()
                ->all(),
            'numbers' => collect(data_get($response, 'data', []))
                ->map(fn (array $item): array => [
                    'number' => $this->format((string) data_get($item, 'number')),
                    'price' => $priceBySku[(string) data_get($item, 'sku')] ?? 0,
                ])
                ->all(),
            'page' => (int) data_get($response, 'meta.currentPage', $page),
            'totalPages' => max(1, (int) data_get($response, 'meta.page.total-pages', 1)),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function categories(?string $clientIp): array
    {
        return Cache::remember(
            'bss.msisdns_categories',
            self::CACHE_TTL,
            fn (): array => data_get($this->bss->categories($clientIp), 'data', []),
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function numbers(string $sku, ?string $mask, int $page, int $size, ?string $clientIp): array
    {
        return Cache::remember(
            "bss.msisdns:{$sku}:{$mask}:{$page}:{$size}",
            self::CACHE_TTL,
            fn (): array => $this->bss->numbers($sku, $mask, $page, $size, $clientIp),
        );
    }

    private function format(string $number): string
    {
        $digits = preg_replace('/\D/', '', $number);
        $local = str_starts_with($digits, '998') ? substr($digits, 3) : $digits;

        if (strlen($local) < 9) {
            return $number;
        }

        $rest = substr($local, 2);

        return '('.substr($local, 0, 2).') '.substr($rest, 0, 3).'-'.substr($rest, 3, 2).'-'.substr($rest, 5, 2);
    }
}
