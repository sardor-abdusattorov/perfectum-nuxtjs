<?php

declare(strict_types=1);

use App\Models\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Cache::flush();
    Settings::forgetValues();
});

function metricsSnippet(int $id = 109871468): string
{
    return <<<HTML
    <script type="text/javascript">
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();
        k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
        })(window, document,'script','https://mc.yandex.ru/metrika/tag.js?id={$id}', 'ym');

        ym({$id}, 'init', {ssr:true, webvisor:true, clickmap:true});
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/{$id}" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    HTML;
}

/**
 * A page view on a route change needs the counter number, and the panel only
 * ever holds the snippet — so the number is read back out of it.
 */
it('reads the counter number out of the code the panel was given', function (): void {
    Settings::set('metrics.yandex', metricsSnippet());
    Settings::forgetValues();

    expect(metrics_counter_ids())->toBe([109871468]);
});

it('finds the number in the pixel when the script is shaped differently', function (): void {
    Settings::set('metrics.yandex', '<noscript><img src="https://mc.yandex.ru/watch/44147844" /></noscript>');
    Settings::forgetValues();

    expect(metrics_counter_ids())->toBe([44147844]);
});

it('takes the number typed by hand over the one in the code', function (): void {
    Settings::set('metrics.yandex', metricsSnippet());
    Settings::set('metrics.yandex_id', '99999999');
    Settings::forgetValues();

    expect(metrics_counter_ids())->toBe([99999999]);
});

it('has no number to offer when nothing is set up', function (): void {
    expect(metrics_counter_ids())->toBe([]);

    Settings::set('metrics.yandex', '<script>console.log("не метрика")</script>');
    Settings::forgetValues();

    expect(metrics_counter_ids())->toBe([]);
});

it('hands the number to the site along with the rest of the settings', function (): void {
    Settings::set('metrics.yandex', metricsSnippet());
    Settings::forgetValues();

    $this->getJson(route('api.v1.site'))
        ->assertOk()
        ->assertJsonPath('data.settings.metrics.enabled', true)
        ->assertJsonPath('data.settings.metrics.yandex_ids', [109871468]);
});

it('keeps the counter code out of the site payload, it is served on its own', function (): void {
    Settings::set('metrics.yandex', metricsSnippet());
    Settings::forgetValues();

    $site = $this->getJson(route('api.v1.site'))->assertOk()->getContent();

    expect($site)->not->toContain('mc.yandex.ru');

    $this->getJson(route('api.v1.metrics'))
        ->assertOk()
        ->assertJsonPath('data.yandex', fn (string $code): bool => str_contains($code, 'mc.yandex.ru'));
});
