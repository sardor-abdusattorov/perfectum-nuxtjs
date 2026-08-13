<?php

declare(strict_types=1);

use App\Models\FreeNumberFilter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

function cdmaXml(array $rows): string
{
    $resources = collect($rows)
        ->map(function (array $row): string {
            $cells = collect($row)
                ->map(fn (string $value, string $tag): string => "<{$tag}>".base64_encode($value)."</{$tag}>")
                ->implode('');

            return "<Resources>{$cells}</Resources>";
        })
        ->implode('');

    return '<Root><MyTagNames>PHONE_ID,ATS,PRICE,EDATE</MyTagNames>'.$resources.'</Root>';
}

function fakeCdma(): void
{
    Http::fake(function ($request) {
        parse_str(parse_url($request->url(), PHP_URL_QUERY) ?? '', $query);

        return match ($query['func'] ?? '') {
            'getPhonePrices' => Http::response([
                ['PCODE' => 20000, 'PRICE' => 20000],
                ['PCODE' => 10000, 'PRICE' => 10000],
            ]),
            'getFreeNumbersNominalCount' => Http::response('17'),
            'getFreeNumbersNominal' => Http::response(cdmaXml([
                ['PHONE_ID' => '2710533', 'ATS' => '271', 'PRICE' => '10000', 'EDATE' => '01.08.2026'],
                ['PHONE_ID' => '0010001', 'ATS' => '001', 'PRICE' => '0', 'EDATE' => '02.08.2026'],
            ])),
            default => Http::response(null, 404),
        };
    });
}

it('serves prefixes from the base and live prices', function (): void {
    fakeCdma();

    FreeNumberFilter::factory()->create(['value' => '271', 'name' => '271']);
    FreeNumberFilter::factory()->create(['value' => '001', 'name' => '001']);
    FreeNumberFilter::factory()->create(['value' => '999', 'name' => '999', 'status' => false]);
    FreeNumberFilter::factory()->price(30000)->create();

    $this->getJson(route('api.v1.cdma-numbers.filters'))
        ->assertOk()
        ->assertJsonCount(2, 'data.prefixes')
        ->assertJsonPath('data.prefixes.0.value', '001')
        ->assertJsonPath('data.prices.0.price', 10000)
        ->assertJsonPath('data.prices.1.price', 20000);
});

it('falls back to stored prices when the gateway is down', function (): void {
    Http::fake(['*' => Http::response(null, 500)]);

    FreeNumberFilter::factory()->price(40000)->create();
    FreeNumberFilter::factory()->price(10000)->create();

    $this->getJson(route('api.v1.cdma-numbers.filters'))
        ->assertOk()
        ->assertJsonPath('data.prices.0.price', 10000)
        ->assertJsonPath('data.prices.1.price', 40000);
});

it('searches numbers and formats the rows', function (): void {
    fakeCdma();

    $this->postJson(route('api.v1.cdma-numbers.search'), ['number' => '0533', 'prefix' => '271'])
        ->assertOk()
        ->assertJsonPath('data.total', 17)
        ->assertJsonPath('data.totalPages', 2)
        ->assertJsonPath('data.numbers.0.number', '271 05 33')
        ->assertJsonPath('data.numbers.0.prefix', '271')
        ->assertJsonPath('data.numbers.0.price', 10000)
        ->assertJsonPath('data.numbers.1.price', 0);

    Http::assertSent(function ($request): bool {
        parse_str(parse_url($request->url(), PHP_URL_QUERY) ?? '', $query);

        return ($query['func'] ?? '') !== 'getFreeNumbersNominal'
            || $query['attributes'] === '0,271,-1,0533,0,15';
    });
});

it('requires the number digits', function (): void {
    $this->postJson(route('api.v1.cdma-numbers.search'), [])->assertStatus(422);
    $this->postJson(route('api.v1.cdma-numbers.search'), ['number' => '12345'])->assertStatus(422);
    $this->postJson(route('api.v1.cdma-numbers.search'), ['number' => '12a4'])->assertStatus(422);
});

it('answers 502 when the gateway is down', function (): void {
    Http::fake(['*' => Http::response(null, 500)]);

    $this->postJson(route('api.v1.cdma-numbers.search'), ['number' => '1234'])
        ->assertStatus(502)
        ->assertJsonPath('message', 'cdma_unavailable');
});
