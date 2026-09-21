<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Env;

uses(RefreshDatabase::class);

/**
 * Без базы эти ручки отвечали пятисоткой, а заголовок CORS ставит middleware —
 * тесты были зелёными и остались бы зелёными, даже если ручки перестали бы
 * отвечать вовсе. Отсюда assertOk у каждого запроса.
 */
it('answers the site and turns a stranger away', function (string $path): void {
    $site = (string) config('cors.allowed_origins')[0];

    $this->getJson("/api/v1/{$path}", ['Origin' => $site])
        ->assertOk()
        ->assertHeader('Access-Control-Allow-Origin', $site);

    $response = $this->getJson("/api/v1/{$path}", ['Origin' => 'https://another-site.uz'])->assertOk();

    expect($response->headers->get('Access-Control-Allow-Origin'))->not->toBe('https://another-site.uz');
    expect($response->headers->get('Access-Control-Allow-Origin'))->not->toBe('*');
})->with(['site', 'faqs', 'offices']);

it('leaves the feeds the old site published open to anyone', function (string $path): void {
    $this->getJson("/api/v1/{$path}", ['Origin' => 'https://another-site.uz'])
        ->assertOk()
        ->assertHeader('Access-Control-Allow-Origin', '*');
})->with(['documents', 'coverage']);

it('falls back to the site, never to the api itself, when FRONTEND_URL is unset', function (): void {
    $repository = Env::getRepository();
    $previous = $repository->get('FRONTEND_URL');

    $repository->clear('FRONTEND_URL');

    try {
        $origins = (require config_path('cors.php'))['allowed_origins'];
    } finally {
        if ($previous !== null) {
            $repository->set('FRONTEND_URL', $previous);
        }
    }

    expect($origins)
        ->toBe(['http://localhost:3000', 'http://127.0.0.1:3000'])
        ->not->toContain(config('app.url'));
});
