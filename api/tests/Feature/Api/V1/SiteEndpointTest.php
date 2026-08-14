<?php

declare(strict_types=1);

use App\Enums\MenuLocation;
use App\Models\Menu;
use App\Models\Settings;
use App\Models\SiteSettings;
use App\Models\SiteTranslation;
use App\Models\Social;
use Database\Seeders\SiteSettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Cache::flush();
});

it('hands the whole site chrome back in one call', function (): void {
    $this->getJson(route('api.v1.site'))
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'settings' => ['locale', 'locales', 'seo', 'metrics', 'site'],
                'menus' => ['header', 'footer'],
                'socials',
                'translations',
            ],
        ]);
});

it('returns seo translated into the requested locale', function (): void {
    Settings::set('seo.title', ['ru' => 'Перфектум', 'uz' => 'Perfektum', 'en' => 'Perfectum']);
    Settings::set('seo.description', ['ru' => 'Описание', 'uz' => 'Tavsif']);

    $this->getJson(route('api.v1.site'), ['X-Locale' => 'uz'])
        ->assertOk()
        ->assertJsonPath('data.settings.locale', 'uz')
        ->assertJsonPath('data.settings.seo.title', 'Perfektum')
        ->assertJsonPath('data.settings.seo.description', 'Tavsif');
});

it('falls back to the fallback locale when a translation is missing', function (): void {
    Settings::set('seo.title', ['ru' => 'Перфектум']);

    $this->getJson(route('api.v1.site'), ['X-Locale' => 'en'])
        ->assertOk()
        ->assertJsonPath('data.settings.seo.title', 'Перфектум');
});

it('rejects an unknown locale and serves the default one', function (): void {
    $this->getJson(route('api.v1.site'), ['X-Locale' => 'de'])
        ->assertOk()
        ->assertJsonPath('data.settings.locale', config('app.locale'));
});

it('reports whether metrics exist without shipping the counter code', function (): void {
    Settings::set('metrics.yandex', '<script>ym()</script>');

    $this->getJson(route('api.v1.site'))
        ->assertOk()
        ->assertJsonPath('data.settings.metrics.enabled', true)
        ->assertDontSee('ym()');
});

it('exposes published site settings only', function (): void {
    SiteSettings::create(['name' => 'phone', 'value' => '+998 98 127 0077', 'is_published' => true]);
    SiteSettings::create(['name' => 'draft', 'value' => 'hidden', 'is_published' => false]);

    $this->getJson(route('api.v1.site'))
        ->assertOk()
        ->assertJsonPath('data.settings.site.phone', '+998 98 127 0077')
        ->assertJsonMissingPath('data.settings.site.draft');
});

it('groups menus by location and nests children', function (): void {
    $parent = Menu::create([
        'location' => MenuLocation::Header,
        'name' => ['ru' => 'Мобильная связь', 'uz' => 'Mobil aloqa'],
        'sort' => 1,
        'status' => true,
    ]);

    Menu::create([
        'parent_id' => $parent->id,
        'location' => MenuLocation::Header,
        'name' => ['ru' => 'Тарифы', 'uz' => 'Tariflar'],
        'sort' => 1,
        'status' => true,
    ]);

    $this->getJson(route('api.v1.site'), ['X-Locale' => 'uz'])
        ->assertOk()
        ->assertJsonPath('data.menus.header.0.name', 'Mobil aloqa')
        ->assertJsonPath('data.menus.header.0.children.0.name', 'Tariflar')
        ->assertJsonPath('data.menus.footer', []);
});

it('hides unpublished menu items', function (): void {
    Menu::create([
        'location' => MenuLocation::Footer,
        'name' => ['ru' => 'Черновик'],
        'sort' => 1,
        'status' => false,
    ]);

    $this->getJson(route('api.v1.site'))
        ->assertOk()
        ->assertJsonPath('data.menus.footer', []);
});

it('refreshes the cached menu when an item changes', function (): void {
    $item = Menu::create([
        'location' => MenuLocation::Header,
        'name' => ['ru' => 'Было'],
        'sort' => 1,
        'status' => true,
    ]);

    $this->getJson(route('api.v1.site'))->assertJsonPath('data.menus.header.0.name', 'Было');

    $item->update(['name' => ['ru' => 'Стало']]);

    $this->getJson(route('api.v1.site'))->assertJsonPath('data.menus.header.0.name', 'Стало');
});

it('returns published networks in the configured order', function (): void {
    Social::create(['name' => 'Instagram', 'icon' => 'si-instagram', 'url' => 'https://instagram.com/x', 'sort' => 2]);
    Social::create(['name' => 'Telegram', 'icon' => 'si-telegram', 'url' => 'https://t.me/x', 'sort' => 1]);
    Social::create(['name' => 'Draft', 'icon' => 'si-x', 'url' => 'https://x.com/x', 'status' => false]);

    $response = $this->getJson(route('api.v1.site'))
        ->assertOk()
        ->assertJsonCount(2, 'data.socials')
        ->assertJsonPath('data.socials.0.name', 'Telegram');

    expect($response->json('data.socials.0.svg'))->toContain('<svg');
});

it('returns published translations keyed by key', function (): void {
    SiteTranslation::create([
        'category' => 'app',
        'key' => 'cookie.accept',
        'value' => ['ru' => 'Принять', 'uz' => 'Qabul qilish'],
        'is_published' => true,
    ]);

    SiteTranslation::create([
        'category' => 'app',
        'key' => 'cookie.draft',
        'value' => ['ru' => 'Черновик'],
        'is_published' => false,
    ]);

    $translations = $this->getJson(route('api.v1.site'), ['X-Locale' => 'uz'])
        ->assertOk()
        ->json('data.translations');

    expect($translations)
        ->toHaveKey('cookie.accept', 'Qabul qilish')
        ->not->toHaveKey('cookie.draft');
});

it('refreshes the cached translations when one changes', function (): void {
    $translation = SiteTranslation::create([
        'category' => 'app',
        'key' => 'cookie.accept',
        'value' => ['ru' => 'Было'],
        'is_published' => true,
    ]);

    expect($this->getJson(route('api.v1.site'))->json('data.translations'))
        ->toHaveKey('cookie.accept', 'Было');

    $translation->update(['value' => ['ru' => 'Стало']]);

    expect($this->getJson(route('api.v1.site'))->json('data.translations'))
        ->toHaveKey('cookie.accept', 'Стало');
});

it('caches the socials as plain data so a second request can read them back', function (): void {
    Social::create(['name' => 'Telegram', 'icon' => 'si-telegram', 'url' => 'https://t.me/x', 'sort' => 1]);

    $first = $this->getJson(route('api.v1.site'))->assertOk()->json('data.socials');

    expect(Cache::get(Social::cacheKey()))->toBeArray();

    $second = $this->getJson(route('api.v1.site'))->assertOk()->json('data.socials');

    expect($second)->toBe($first);
});

it('caches the menus as plain data so a second request can read them back', function (): void {
    Menu::create([
        'location' => MenuLocation::Header,
        'name' => ['ru' => 'Тарифы'],
        'url' => ['ru' => '/tariffs'],
        'sort' => 1,
        'status' => true,
    ]);

    $first = $this->getJson(route('api.v1.site'), ['X-Locale' => 'ru'])->assertOk()->json('data.menus.header');

    expect(Cache::get(Menu::cacheKey(MenuLocation::Header, 'ru')))->toBeArray();

    $second = $this->getJson(route('api.v1.site'), ['X-Locale' => 'ru'])->assertOk()->json('data.menus.header');

    expect($second)->toBe($first);
});

it('ships both stores of the mobile app', function (): void {
    $this->seed(SiteSettingsSeeder::class);

    $settings = $this->getJson(route('api.v1.site'))
        ->assertOk()
        ->json('data.settings.site');

    expect($settings['app_store_url'])->toStartWith('https://apps.apple.com/');
    expect($settings['google_play_url'])->toStartWith('https://play.google.com/');
});
