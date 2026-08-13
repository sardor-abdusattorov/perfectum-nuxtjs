<?php

declare(strict_types=1);

namespace App\Services\Cdma;

use DOMDocument;
use DOMElement;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class CdmaClient
{
    /**
     * @return array<int, array<string, int>>
     */
    public function prices(): array
    {
        $rows = $this->http()
            ->get($this->endpoint('prices'), [
                'attributes' => '',
                'dtypes' => '',
                'func' => 'getPhonePrices',
            ])
            ->throw()
            ->json() ?? [];

        return collect($rows)
            ->map(fn (array $row): array => [
                'code' => (int) data_get($row, 'PCODE'),
                'price' => (int) data_get($row, 'PRICE'),
            ])
            ->sortBy('price')
            ->values()
            ->all();
    }

    public function count(string $prefix, int $price, string $number, int $region = 0): int
    {
        $body = $this->http()
            ->get($this->endpoint('numbers'), [
                'attributes' => "{$region},{$prefix},{$price},{$number}",
                'dtypes' => 'n,n,n,n',
                'func' => 'getFreeNumbersNominalCount',
            ])
            ->throw()
            ->body();

        return (int) trim($body);
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function numbers(string $prefix, int $price, string $number, int $first, int $last, int $region = 0): array
    {
        $body = $this->http()
            ->get($this->endpoint('numbers'), [
                'attributes' => "{$region},{$prefix},{$price},{$number},{$first},{$last}",
                'dtypes' => 'n,n,n,n,n,n',
                'func' => 'getFreeNumbersNominal',
            ])
            ->throw()
            ->body();

        return $this->parse($body);
    }

    /**
     * The gateway answers XML where MyTagNames lists the columns and every
     * Resources element carries them base64-encoded.
     *
     * @return array<int, array<string, string>>
     */
    private function parse(string $xml): array
    {
        $document = new DOMDocument;

        if ($xml === '' || ! @$document->loadXML($xml)) {
            throw new RuntimeException('Unreadable CDMA gateway response');
        }

        $names = $document->getElementsByTagName('MyTagNames')->item(0)?->nodeValue;

        if (blank($names)) {
            throw new RuntimeException('CDMA gateway response misses MyTagNames');
        }

        $columns = array_filter(explode(',', (string) $names), fn (string $column): bool => $column !== '0');

        $rows = [];

        foreach ($document->getElementsByTagName('Resources') as $resource) {
            /** @var DOMElement $resource */
            $row = [];

            foreach ($columns as $column) {
                $row[$column] = base64_decode($resource->getElementsByTagName($column)->item(0)?->nodeValue ?? '');
            }

            $rows[] = $row;
        }

        return $rows;
    }

    private function http(): PendingRequest
    {
        return Http::baseUrl(rtrim((string) config('services.cdma.url'), '/'))
            ->timeout((int) config('services.cdma.timeout', 20));
    }

    private function endpoint(string $key): string
    {
        return (string) config("services.cdma.endpoints.{$key}");
    }
}
