<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config()->set('cors.allowed_origins', ['https://perfectum.uz']);
});

it('lets the site through', function (): void {
    $this->getJson(route('api.v1.news.index'), ['Origin' => 'https://perfectum.uz'])
        ->assertOk()
        ->assertHeader('Access-Control-Allow-Origin', 'https://perfectum.uz');
});

/**
 * The header is not withheld — it names the one origin allowed. The browser
 * compares it against its own and refuses to hand the answer to anyone else,
 * so what has to hold is that a stranger's own address never comes back.
 */
it('never names a foreign site as allowed', function (): void {
    $response = $this->getJson(route('api.v1.news.index'), ['Origin' => 'https://zloy-sayt.uz']);

    expect($response->headers->get('Access-Control-Allow-Origin'))
        ->not->toBe('https://zloy-sayt.uz')
        ->not->toBe('*');
});

it('names no foreign site on a preflight either', function (): void {
    $response = $this->call('OPTIONS', route('api.v1.news.index'), server: [
        'HTTP_ORIGIN' => 'https://zloy-sayt.uz',
        'HTTP_ACCESS_CONTROL_REQUEST_METHOD' => 'GET',
    ]);

    expect($response->headers->get('Access-Control-Allow-Origin'))
        ->not->toBe('https://zloy-sayt.uz')
        ->not->toBe('*');
});

/**
 * The map and the documents feed answer the mobile app, which sends no origin
 * a list could name, so those two stay open on purpose.
 */
it('keeps the handful of endpoints the mobile app needs open to everyone', function (): void {
    $this->getJson(route('api.v1.documents'), ['Origin' => 'https://zloy-sayt.uz'])
        ->assertOk()
        ->assertHeader('Access-Control-Allow-Origin', '*');
});

it('reads the allowed origins out of FRONTEND_URL', function (): void {
    $origins = array_values(array_filter(array_map(
        'trim',
        explode(',', 'https://perfectum.uz, https://www.perfectum.uz')
    )));

    expect($origins)->toBe(['https://perfectum.uz', 'https://www.perfectum.uz']);
});
