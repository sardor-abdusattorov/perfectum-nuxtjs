<?php

declare(strict_types=1);

namespace App\Services\Cdma;

use Illuminate\Support\Facades\Cache;
use RuntimeException;
use Throwable;

class FreeNumberService
{
    public const PAGE_SIZE = 15;

    private const CACHE_TTL = 30;

    private const PRICES_TTL = 300;

    private const OUTAGE_KEY = 'cdma.outage';

    private const OUTAGE_TTL = 60;

    public function __construct(private readonly CdmaClient $cdma) {}

    /**
     * @return array<int, array<string, int>>
     */
    public function prices(): array
    {
        return $this->throughGateway(fn (): array => Cache::remember(
            'cdma.prices',
            self::PRICES_TTL,
            fn (): array => $this->cdma->prices(),
        ));
    }

    /**
     * A gateway that is down answers by timing out, and the timeout is the
     * whole cost: the CDMA page asks for the filters on every visit, and each
     * visit would hold a worker for it. One request discovers the outage; for
     * the minute after, the rest are refused straight away and fall back to
     * what the panel holds without touching the network.
     *
     * @template T
     *
     * @param  callable(): T  $call
     * @return T
     */
    private function throughGateway(callable $call): mixed
    {
        if (Cache::get(self::OUTAGE_KEY) === true) {
            throw new RuntimeException('cdma gateway is down');
        }

        try {
            return $call();
        } catch (Throwable $exception) {
            Cache::put(self::OUTAGE_KEY, true, self::OUTAGE_TTL);

            throw $exception;
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function search(string $number, string $prefix, int $price, int $page): array
    {
        return $this->throughGateway(fn (): array => Cache::remember(
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
        ));
    }

    private function format(string $number): string
    {
        if (strlen($number) < 6) {
            return $number;
        }

        return substr($number, 0, 3).' '.substr($number, 3, 2).' '.substr($number, 5);
    }
}
