<?php

declare(strict_types=1);

use App\Models\FreeNumberFilter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Cache::flush();

    FreeNumberFilter::create([
        'type' => FreeNumberFilter::TYPE_PREFIX, 'name' => '271', 'value' => '271', 'status' => true,
    ]);
    FreeNumberFilter::create([
        'type' => FreeNumberFilter::TYPE_PRICE, 'name' => '10 000', 'value' => '10000', 'status' => true,
    ]);
});

/**
 * The CDMA page asks for the filters on every visit, and a gateway that is
 * down answers by timing out. Paying that timeout once per visitor is what
 * empties a worker pool, so the outage is remembered and the rest are served
 * from what the panel holds.
 */
it('reaches a dead gateway once and serves the panel prices after that', function (): void {
    $reached = 0;
    Http::fake(function () use (&$reached) {
        $reached++;

        throw new ConnectionException('connection timed out');
    });

    $this->getJson(route('api.v1.cdma-numbers.filters'))
        ->assertOk()
        ->assertJsonPath('data.prices.0.price', 10000)
        ->assertJsonPath('data.prefixes.0.value', '271');

    expect($reached)->toBe(1);

    foreach (range(1, 5) as $ignored) {
        $this->getJson(route('api.v1.cdma-numbers.filters'))
            ->assertOk()
            ->assertJsonPath('data.prices.0.price', 10000);
    }

    expect($reached)->toBe(1);
});

it('refuses a number search while the gateway is known to be down', function (): void {
    $reached = 0;
    Http::fake(function () use (&$reached) {
        $reached++;

        throw new ConnectionException('connection timed out');
    });

    $this->postJson(route('api.v1.cdma-numbers.search'), ['number' => '55'])
        ->assertStatus(502)
        ->assertJsonPath('message', 'cdma_unavailable');

    expect($reached)->toBe(1);

    $this->postJson(route('api.v1.cdma-numbers.search'), ['number' => '77'])
        ->assertStatus(502);

    expect($reached)->toBe(1);
});

it('goes back to the gateway once the outage has been forgotten', function (): void {
    $down = true;
    Http::fake(function () use (&$down) {
        if ($down) {
            throw new ConnectionException('connection timed out');
        }

        return Http::response([['PCODE' => 20000, 'PRICE' => 20000]]);
    });

    $this->getJson(route('api.v1.cdma-numbers.filters'))
        ->assertOk()
        ->assertJsonPath('data.prices.0.price', 10000);

    $down = false;

    $this->getJson(route('api.v1.cdma-numbers.filters'))
        ->assertOk()
        ->assertJsonPath('data.prices.0.price', 10000);

    Cache::forget('cdma.outage');

    $this->getJson(route('api.v1.cdma-numbers.filters'))
        ->assertOk()
        ->assertJsonPath('data.prices.0.price', 20000);
});
