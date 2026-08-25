<?php

declare(strict_types=1);

use App\Models\News;
use App\Models\Page;
use App\Support\PreviewToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Testing\TestResponse;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Cache::flush();
});

function draftPage(array $attributes = []): Page
{
    return Page::create(array_merge([
        'slug' => 'chernovik',
        'title' => ['ru' => 'Черновик', 'uz' => 'Qoralama'],
        'content' => ['ru' => '<p>Ещё не опубликовано</p>', 'uz' => '<p>Hali chop etilmagan</p>'],
        'status' => false,
    ], $attributes));
}

function preview(string $slug, string $token): TestResponse
{
    return test()->getJson(route('api.v1.pages.show', ['page' => $slug]).'?'.PreviewToken::PARAM.'='.urlencode($token));
}

it('opens a draft for the token that names it', function (): void {
    $page = draftPage();

    preview('chernovik', PreviewToken::for($page))
        ->assertOk()
        ->assertJsonPath('data.slug', 'chernovik')
        ->assertJsonPath('data.content', '<p>Ещё не опубликовано</p>');
});

it('keeps the draft a 404 without a token', function (): void {
    draftPage();

    $this->getJson(route('api.v1.pages.show', ['page' => 'chernovik']))->assertNotFound();
});

/**
 * A link travels further than it was meant to. It still opens the one record it
 * was made for and nothing else.
 */
it('refuses a token issued for another record', function (): void {
    draftPage();
    $other = draftPage(['slug' => 'drugoy']);

    preview('chernovik', PreviewToken::for($other))->assertNotFound();
});

it('refuses a token issued for another kind of record', function (): void {
    $page = draftPage();

    $news = News::create([
        'title' => ['ru' => 'Новость'],
        'slug' => 'novost',
        'content' => ['ru' => '<p>Текст</p>'],
        'published_at' => now()->subDay(),
        'status' => false,
    ]);

    expect($page->getKey())->toBe($news->getKey());

    preview('chernovik', PreviewToken::for($news))->assertNotFound();
});

it('refuses a token whose day has passed', function (): void {
    $page = draftPage();

    $stale = Crypt::encryptString(json_encode([
        'type' => $page->getMorphClass(),
        'key' => $page->getKey(),
        'until' => now()->subMinute()->getTimestamp(),
    ], JSON_THROW_ON_ERROR));

    preview('chernovik', $stale)->assertNotFound();
});

it('refuses a token that was tampered with or made up', function (): void {
    draftPage();

    foreach (['', 'не-токен', str_repeat('x', 120)] as $token) {
        preview('chernovik', $token)->assertNotFound();
    }
});

/**
 * The token opens one address, never a listing: a draft that showed up in the
 * feed or the search would be published in every way that matters.
 */
it('never lets a draft into a listing', function (): void {
    $draft = News::create([
        'title' => ['ru' => 'Черновик новости'],
        'slug' => 'chernovik-novosti',
        'content' => ['ru' => '<p>Текст</p>'],
        'published_at' => now()->subDay(),
        'status' => false,
    ]);

    News::create([
        'title' => ['ru' => 'Живая новость'],
        'slug' => 'zhivaya-novost',
        'content' => ['ru' => '<p>Текст</p>'],
        'published_at' => now()->subDay(),
        'status' => true,
    ]);

    $token = urlencode(PreviewToken::for($draft));

    $slugs = $this->getJson(route('api.v1.news.index').'?'.PreviewToken::PARAM.'='.$token)
        ->assertOk()
        ->json('data.*.slug');

    expect($slugs)->toContain('zhivaya-novost')
        ->and($slugs)->not->toContain('chernovik-novosti');

    /** its own address still opens, which is the whole point */
    $this->getJson(route('api.v1.news.show', ['news' => 'chernovik-novosti']).'?'.PreviewToken::PARAM.'='.$token)
        ->assertOk()
        ->assertJsonPath('data.slug', 'chernovik-novosti');
});

/**
 * The page endpoint caches what it answers. A preview must not be served to the
 * next visitor, and must not be answered from a copy made before the last save.
 */
it('keeps a preview out of the cache', function (): void {
    $page = draftPage();

    preview('chernovik', PreviewToken::for($page))->assertOk();

    expect(Cache::has(Page::cacheKey('chernovik', app()->getLocale())))->toBeFalse();

    $this->getJson(route('api.v1.pages.show', ['page' => 'chernovik']))->assertNotFound();
});

it('answers a published page from the cache as before', function (): void {
    draftPage(['slug' => 'zhivaya', 'status' => true]);

    $this->getJson(route('api.v1.pages.show', ['page' => 'zhivaya']))->assertOk();

    expect(Cache::has(Page::cacheKey('zhivaya', app()->getLocale())))->toBeTrue();
});
