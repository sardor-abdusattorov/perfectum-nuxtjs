<?php

declare(strict_types=1);

use App\Models\Page;
use App\Models\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

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
