<?php

declare(strict_types=1);

use App\Models\Page;
use App\Models\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Cache::flush();
});

function makePage(array $attributes = []): Page
{
    return Page::create(array_merge([
        'slug' => 'cookie-policy',
        'title' => ['ru' => 'Политика cookie', 'uz' => 'Cookie siyosati'],
        'content' => ['ru' => '<p>Текст</p>', 'uz' => '<p>Matn</p>'],
        'status' => true,
    ], $attributes));
}

it('serves a published page by slug in the requested locale', function (): void {
    makePage();

    $this->getJson(route('api.v1.pages.show', ['page' => 'cookie-policy']), ['X-Locale' => 'uz'])
        ->assertOk()
        ->assertJsonPath('data.slug', 'cookie-policy')
        ->assertJsonPath('data.title', 'Cookie siyosati')
        ->assertJsonPath('data.content', '<p>Matn</p>');
});

it('hides an unpublished page', function (): void {
    makePage(['status' => false]);

    $this->getJson(route('api.v1.pages.show', ['page' => 'cookie-policy']))->assertNotFound();
});

it('returns not found for an unknown slug', function (): void {
    $this->getJson(route('api.v1.pages.show', ['page' => 'nothing-here']))->assertNotFound();
});

it('falls back to the page title and the global seo when meta fields are empty', function (): void {
    Settings::set('seo.description', ['ru' => 'Описание сайта']);
    makePage();

    $this->getJson(route('api.v1.pages.show', ['page' => 'cookie-policy']))
        ->assertOk()
        ->assertJsonPath('data.seo.title', 'Политика cookie')
        ->assertJsonPath('data.seo.description', 'Описание сайта');
});

it('always takes the keywords from the main settings', function (): void {
    Settings::set('seo.keywords', ['ru' => 'perfectum, 5g']);
    makePage();

    $this->getJson(route('api.v1.pages.show', ['page' => 'cookie-policy']))
        ->assertOk()
        ->assertJsonPath('data.seo.keywords', 'perfectum, 5g');
});

it('prefers the page meta fields over the global seo', function (): void {
    Settings::set('seo.description', ['ru' => 'Описание сайта']);
    makePage([
        'meta_title' => ['ru' => 'Cookie — Perfectum'],
        'meta_description' => ['ru' => 'Как мы используем cookie'],
    ]);

    $this->getJson(route('api.v1.pages.show', ['page' => 'cookie-policy']))
        ->assertOk()
        ->assertJsonPath('data.seo.title', 'Cookie — Perfectum')
        ->assertJsonPath('data.seo.description', 'Как мы используем cookie');
});

it('refreshes the cache when the page changes', function (): void {
    $page = makePage();

    $this->getJson(route('api.v1.pages.show', ['page' => 'cookie-policy']))
        ->assertJsonPath('data.title', 'Политика cookie');

    $page->update(['title' => ['ru' => 'Обновлено']]);

    $this->getJson(route('api.v1.pages.show', ['page' => 'cookie-policy']))
        ->assertJsonPath('data.title', 'Обновлено');
});

it('drops the old cache entry when the slug changes', function (): void {
    $page = makePage();

    $this->getJson(route('api.v1.pages.show', ['page' => 'cookie-policy']))->assertOk();

    $page->update(['slug' => 'cookie']);

    $this->getJson(route('api.v1.pages.show', ['page' => 'cookie-policy']))->assertNotFound();
    $this->getJson(route('api.v1.pages.show', ['page' => 'cookie']))->assertOk();
});

it('caches the page as plain data so a second request can read it back', function (): void {
    makePage();

    $first = $this->getJson(route('api.v1.pages.show', ['page' => 'cookie-policy']), ['X-Locale' => 'ru'])
        ->assertOk()
        ->json('data');

    expect(Cache::get(Page::cacheKey('cookie-policy', 'ru')))->toBeArray();

    $second = $this->getJson(route('api.v1.pages.show', ['page' => 'cookie-policy']), ['X-Locale' => 'ru'])
        ->assertOk()
        ->json('data');

    expect($second)->toBe($first);
});

it('hands the site the cards of a hub page in the order the admin set', function (): void {
    $hub = makePage(['slug' => 'polezno-znat', 'content' => null]);

    makePage(['slug' => 'vtoraya', 'parent_id' => $hub->getKey(), 'sort' => 2, 'title' => ['ru' => 'Вторая']]);
    makePage(['slug' => 'pervaya', 'parent_id' => $hub->getKey(), 'sort' => 1, 'title' => ['ru' => 'Первая']]);

    $this->getJson(route('api.v1.pages.show', ['page' => 'polezno-znat']))
        ->assertOk()
        ->assertJsonPath('data.content', null)
        ->assertJsonPath('data.parent', null)
        ->assertJsonPath('data.cards.0.slug', 'pervaya')
        ->assertJsonPath('data.cards.0.title', 'Первая')
        ->assertJsonPath('data.cards.1.slug', 'vtoraya');
});

it('keeps an unpublished card off the hub page', function (): void {
    $hub = makePage(['slug' => 'polezno-znat']);
    makePage(['slug' => 'chernovik', 'parent_id' => $hub->getKey(), 'status' => false]);

    $this->getJson(route('api.v1.pages.show', ['page' => 'polezno-znat']))
        ->assertOk()
        ->assertJsonPath('data.cards', []);
});

it('points a card page back at the hub it belongs to', function (): void {
    $hub = makePage(['slug' => 'polezno-znat', 'title' => ['ru' => 'Полезно знать']]);
    makePage(['slug' => 'tarify', 'parent_id' => $hub->getKey()]);

    $this->getJson(route('api.v1.pages.show', ['page' => 'tarify']))
        ->assertOk()
        ->assertJsonPath('data.parent.slug', 'polezno-znat')
        ->assertJsonPath('data.parent.title', 'Полезно знать')
        ->assertJsonPath('data.cards', []);
});

it('refreshes the hub page when one of its cards is renamed', function (): void {
    $hub = makePage(['slug' => 'polezno-znat']);
    $card = makePage(['slug' => 'tarify', 'parent_id' => $hub->getKey(), 'title' => ['ru' => 'Тарифы']]);

    $this->getJson(route('api.v1.pages.show', ['page' => 'polezno-znat']))
        ->assertJsonPath('data.cards.0.title', 'Тарифы');

    $card->update(['title' => ['ru' => 'Наши тарифы']]);

    $this->getJson(route('api.v1.pages.show', ['page' => 'polezno-znat']))
        ->assertJsonPath('data.cards.0.title', 'Наши тарифы');
});

it('drops a deleted page from the hub it was on', function (): void {
    $hub = makePage(['slug' => 'polezno-znat']);
    $card = makePage(['slug' => 'tarify', 'parent_id' => $hub->getKey()]);

    $this->getJson(route('api.v1.pages.show', ['page' => 'polezno-znat']))
        ->assertJsonCount(1, 'data.cards');

    $card->delete();

    $this->getJson(route('api.v1.pages.show', ['page' => 'polezno-znat']))
        ->assertJsonPath('data.cards', []);
});

it('empties a page that becomes a group, so nothing comes back later', function (): void {
    Storage::fake('public');
    Storage::disk('public')->put('uploads/pages/hero.jpg', 'jpg');

    $page = makePage([
        'image' => 'uploads/pages/hero.jpg',
        'meta_title' => ['ru' => 'Старый заголовок'],
        'meta_description' => ['ru' => 'Старое описание'],
        'redirect_from' => ['static-pages/oferta'],
    ]);

    $page->update(['is_group' => true]);

    $page->refresh();

    expect($page->content)->toBeEmpty()
        ->and($page->image)->toBeNull()
        ->and($page->meta_title)->toBeEmpty()
        ->and($page->meta_description)->toBeEmpty()
        ->and($page->redirect_from)->toBeNull();

    Storage::disk('public')->assertMissing('uploads/pages/hero.jpg');
});

it('hands the site the card text from the page it points at', function (): void {
    $hub = makePage(['slug' => 'polezno-znat', 'is_group' => true]);

    makePage([
        'slug' => 'o-standarte',
        'parent_id' => $hub->getKey(),
        'content' => ['ru' => '<p>Сеть «Perfectum» использует технологию CDMA 2000 1X.</p>'],
    ]);

    $this->getJson(route('api.v1.pages.show', ['page' => 'polezno-znat']))
        ->assertOk()
        ->assertJsonPath('data.is_group', true)
        ->assertJsonPath('data.cards.0.text', 'Сеть «Perfectum» использует технологию CDMA 2000 1X.');
});

it('maps every old address onto its page', function (): void {
    makePage(['redirect_from' => ['static-pages/cookie-policy', 'static-pages/cookies']]);
    makePage([
        'slug' => 'oferta',
        'redirect_from' => ['static-pages/oferta'],
    ]);
    makePage(['slug' => 'plain']);

    $this->getJson(route('api.v1.redirects'))
        ->assertOk()
        ->assertExactJson(['data' => [
            'static-pages/cookie-policy' => '/pages/cookie-policy',
            'static-pages/cookies' => '/pages/cookie-policy',
            'static-pages/oferta' => '/pages/oferta',
        ]]);
});

it('drops an unpublished page from the redirect map', function (): void {
    makePage(['status' => false, 'redirect_from' => ['static-pages/cookie-policy']]);

    $this->getJson(route('api.v1.redirects'))
        ->assertOk()
        ->assertExactJson(['data' => []]);
});

it('boils a pasted address down to the bare path', function (): void {
    $page = makePage(['redirect_from' => [
        'https://perfectum.uz/static-pages/oferta?utm_source=ad',
        '/ru/static-pages/oferta/',
        ' static-pages/oferta ',
        '',
    ]]);

    expect($page->redirect_from)->toBe(['static-pages/oferta']);
});

it('serves a fresh map as soon as a page changes its old addresses', function (): void {
    $page = makePage(['redirect_from' => ['static-pages/old']]);

    $this->getJson(route('api.v1.redirects'))
        ->assertJsonPath('data.static-pages/old', '/pages/cookie-policy');

    $page->update(['redirect_from' => ['static-pages/new']]);

    $this->getJson(route('api.v1.redirects'))
        ->assertExactJson(['data' => ['static-pages/new' => '/pages/cookie-policy']]);
});
