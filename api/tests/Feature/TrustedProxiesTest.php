<?php

declare(strict_types=1);

use App\Models\Application;
use App\Models\ApplicationTheme;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function sendApplicationFrom(string $forwarded): void
{
    $theme = ApplicationTheme::create([
        'name' => ['ru' => 'Подключение'],
        'sort' => 1,
        'status' => true,
    ]);

    test()->postJson(
        route('api.v1.applications.store'),
        ['phone' => '998901234567', 'theme' => $theme->getKey(), 'message' => 'Перезвоните'],
        ['X-Forwarded-For' => $forwarded],
    )->assertCreated();
}

/**
 * $request->ip() carries every rate limit and lands in the application record,
 * so a caller that is not a proxy must not be able to name its own address.
 */
it('ignores a forwarded address sent by someone who is not a trusted proxy', function (): void {
    config(['trustedproxy.proxies' => '10.11.33.20']);

    sendApplicationFrom('9.9.9.9');

    expect(Application::query()->first()->ip_address)->toBe('127.0.0.1');
});

it('takes the forwarded address when the trusted proxy passes it on', function (): void {
    config(['trustedproxy.proxies' => '127.0.0.1']);

    sendApplicationFrom('9.9.9.9');

    expect(Application::query()->first()->ip_address)->toBe('9.9.9.9');
});

/**
 * The deploy runs config:cache, and after that .env is never read again — a
 * value taken straight from env() in bootstrap would silently fall back to its
 * default. Config files are baked into that cache, so this one survives.
 */
it('keeps the setting where config caching can reach it', function (): void {
    expect(file_get_contents(base_path('bootstrap/app.php')))->not->toContain('trustProxies')
        ->and(file_get_contents(config_path('trustedproxy.php')))
        ->toContain("env('TRUSTED_PROXIES', '127.0.0.1')");
});
