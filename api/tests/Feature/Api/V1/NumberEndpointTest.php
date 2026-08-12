<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

function fakeBss(): void
{
    Http::fake([
        '*/msisdns-category' => Http::response(['data' => [
            ['sku' => 'FREE', 'name' => 'Обычные', 'price' => 0],
            ['sku' => 'GOLD', 'name' => 'Золотые', 'price' => 1000000],
        ]]),
        '*/msisdns' => Http::response([
            'data' => [
                ['number' => '998801234567', 'sku' => 'GOLD'],
                ['number' => '998807654321', 'sku' => 'FREE'],
            ],
            'meta' => ['currentPage' => 1, 'page' => ['total-pages' => 3]],
        ]),
    ]);
}

it('serves formatted numbers with their category price', function (): void {
    fakeBss();

    $this->postJson(route('api.v1.numbers'), ['sku' => 'GOLD', 'page' => 1])
        ->assertOk()
        ->assertJsonPath('data.numbers.0.number', '(80) 123-45-67')
        ->assertJsonPath('data.numbers.0.price', 1000000)
        ->assertJsonPath('data.numbers.1.price', 0)
        ->assertJsonPath('data.categories.1.sku', 'GOLD')
        ->assertJsonPath('data.totalPages', 3);
});

it('passes the mask through and drops the empty one', function (): void {
    fakeBss();

    $this->postJson(route('api.v1.numbers'), ['mask' => '80*******'])->assertOk();
    $this->postJson(route('api.v1.numbers'), ['mask' => '801**45**'])->assertOk();

    Http::assertSent(function ($request): bool {
        $body = $request->data();

        return ! str_contains($request->url(), 'msisdns-category')
            ? blank(data_get($body, 'data.mask')) || data_get($body, 'data.mask') === '801**45**'
            : true;
    });
});

it('rejects a malformed mask', function (): void {
    $this->postJson(route('api.v1.numbers'), ['mask' => '12345'])->assertStatus(422);
});

it('answers 502 when the billing api is down', function (): void {
    Http::fake(['*' => Http::response(null, 500)]);

    $this->postJson(route('api.v1.numbers'))
        ->assertStatus(502)
        ->assertJsonPath('message', 'bss_unavailable');
});
