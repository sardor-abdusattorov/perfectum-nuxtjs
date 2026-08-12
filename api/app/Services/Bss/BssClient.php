<?php

declare(strict_types=1);

namespace App\Services\Bss;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class BssClient
{
    /**
     * @return array<string, mixed>
     */
    public function categories(?string $clientIp = null): array
    {
        return $this->http($clientIp)
            ->get($this->endpoint('msisdns_categories'))
            ->throw()
            ->json() ?? [];
    }

    /**
     * @return array<string, mixed>
     */
    public function numbers(string $sku = '', ?string $mask = null, int $page = 1, int $size = 10, ?string $clientIp = null): array
    {
        $data = array_filter([
            'sku' => $sku,
            'mask' => $mask,
        ], fn ($value): bool => filled($value));

        return $this->http($clientIp)->post($this->endpoint('msisdns'), [
            'data' => (object) $data,
            'page' => ['number' => $page, 'size' => $size],
        ])->throw()->json() ?? [];
    }

    private function http(?string $clientIp): PendingRequest
    {
        $headers = ['Authorization' => (string) config('services.billing.token')];

        $ipHeader = (string) config('services.billing.client_ip_header', 'X-Forwarded-For');

        if ($ipHeader !== '' && filled($clientIp)) {
            $headers[$ipHeader] = $clientIp;
        }

        return Http::baseUrl(rtrim((string) config('services.billing.url'), '/'))
            ->acceptJson()
            ->withHeaders($headers)
            ->timeout((int) config('services.billing.timeout', 30));
    }

    private function endpoint(string $key): string
    {
        return (string) config("services.billing.endpoints.{$key}");
    }
}
