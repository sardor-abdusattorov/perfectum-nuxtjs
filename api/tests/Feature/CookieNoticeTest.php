<?php

declare(strict_types=1);

use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Models\ContentBlock;
use App\Models\SiteTranslation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Cache::flush();
});

function cookieNotice(array $data): void
{
    ContentBlock::write(PageKey::Home, ContentBlockKey::Cookie, $data);

    clear_content_blocks_cache(PageKey::Home);
}

/**
 * The notice used to be two translation rows, so it could only ever be flat
 * text — nowhere to put the link to the policy. It is an editor field now.
 */
it('serves the notice as the html the editor wrote', function (): void {
    cookieNotice([
        'text' => ['ru' => '<p>Мы используем cookie. <a href="/ru/pages/cookie">Подробнее</a></p>'],
        'accept' => ['ru' => 'Принять'],
    ]);

    $this->getJson(route('api.v1.site'))
        ->assertOk()
        ->assertJsonPath('data.settings.cookie.text', '<p>Мы используем cookie. <a href="/ru/pages/cookie">Подробнее</a></p>')
        ->assertJsonPath('data.settings.cookie.accept', 'Принять');
});

/**
 * The bar stands on every page, so it has to ride in the shared payload — the
 * homepage blocks are only fetched on the homepage itself.
 */
it('hands the notice to every page, not only the one it is edited on', function (): void {
    cookieNotice(['text' => ['ru' => '<p>Текст</p>'], 'accept' => ['ru' => 'Принять']]);

    $site = $this->getJson(route('api.v1.site'))->assertOk()->json('data.settings.cookie');

    expect($site['text'])->toBe('<p>Текст</p>');
});

it('answers in the requested language', function (): void {
    cookieNotice([
        'text' => ['ru' => '<p>Мы используем cookie</p>', 'uz' => '<p>Biz cookie ishlatamiz</p>'],
        'accept' => ['ru' => 'Принять', 'uz' => 'Qabul qilaman'],
    ]);

    $this->getJson(route('api.v1.site'), ['X-Locale' => 'uz'])
        ->assertOk()
        ->assertJsonPath('data.settings.cookie.text', '<p>Biz cookie ishlatamiz</p>')
        ->assertJsonPath('data.settings.cookie.accept', 'Qabul qilaman');
});

it('leaves the notice empty until someone writes it', function (): void {
    $this->getJson(route('api.v1.site'))
        ->assertOk()
        ->assertJsonPath('data.settings.cookie.text', null)
        ->assertJsonPath('data.settings.cookie.accept', null);
});

it('takes the old translation rows away, they have no reader left', function (): void {
    foreach (['cookie.text', 'cookie.accept'] as $key) {
        SiteTranslation::create(['category' => 'app', 'key' => $key, 'value' => ['ru' => 'Старое']]);
    }

    SiteTranslation::create(['category' => 'app', 'key' => 'footer.address', 'value' => ['ru' => 'Ташкент']]);

    $migration = require database_path('migrations/2026_09_09_120000_move_cookie_notice_out_of_translations.php');
    $migration->up();

    expect(DB::table('site_translations')->pluck('key')->all())->toBe(['footer.address']);
});

it('falls back past a locale whose tab was opened and left empty', function (): void {
    cookieNotice([
        'text' => ['ru' => '<p>Мы используем cookie</p>', 'uz' => ''],
        'accept' => ['ru' => 'Принять', 'uz' => ''],
    ]);

    $this->getJson(route('api.v1.site'), ['X-Locale' => 'uz'])
        ->assertOk()
        ->assertJsonPath('data.settings.cookie.text', '<p>Мы используем cookie</p>')
        ->assertJsonPath('data.settings.cookie.accept', 'Принять');
});
