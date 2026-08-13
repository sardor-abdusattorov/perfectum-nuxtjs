<?php

declare(strict_types=1);

namespace App\Services\Cdma;

use Illuminate\Support\Facades\Cache;

class FreeNumberService
{
    public const PAGE_SIZE = 15;

    private const CACHE_TTL = 30;

    private const PRICES_TTL = 300;

    public function __construct(private readonly CdmaClient $cdma) {}

    /**
     * @return array<int, array<string, int>>
     */
    public function prices(): array
    {
        return Cache::remember(
            'cdma.prices',
            self::PRICES_TTL,
            fn (): array => $this->cdma->prices(),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function search(string $number, string $prefix, int $price, int $page): array
    {
        return Cache::remember(
            "cdma.numbers:{$prefix}:{$price}:{$number}:{$page}",
            self::CACHE_TTL,
            function () use ($number, $prefix, $price, $page): array {
                $take = self::PAGE_SIZE;

                $total = $this->cdma->count($prefix, $price, $number);

                $rows = $total > 0
                    ? $this->cdma->numbers($prefix, $price, $number, ($page - 1) * $take, $page * $take)
                    : [];

                return [
                    'numbers' => collect($rows)
                        ->map(fn (array $row): array => [
                            'number' => $this->format((string) data_get($row, 'PHONE_ID')),
                            'prefix' => (string) data_get($row, 'ATS'),
                            'price' => (int) data_get($row, 'PRICE'),
                            'date' => (string) data_get($row, 'EDATE'),
                        ])
                        ->all(),
                    'total' => $total,
                    'page' => $page,
                    'totalPages' => max(1, (int) ceil($total / $take)),
                ];
            },
        );
    }

    private function format(string $number): string
    {
        if (strlen($number) < 6) {
            return $number;
        }

        return substr($number, 0, 3).' '.substr($number, 3, 2).' '.substr($number, 5);
    }
}
