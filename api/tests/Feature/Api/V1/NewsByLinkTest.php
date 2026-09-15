<?php

declare(strict_types=1);

use App\Filament\Support\PreviewAction;
use App\Models\News;
use App\Support\PreviewToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Cache::flush();
});

function pendingNews(array $attributes = []): News
{
    return News::create(array_merge([
        'title' => ['ru' => 'Новость на согласовании'],
        'slug' => 'na-soglasovanii',
        'content' => ['ru' => '<p>Текст</p>'],
        'published_at' => now()->subDay(),
        'status' => false,
    ], $attributes));
}

/**
 * A preview token names one record for one day, and a story can sit on
 * someone's approval for weeks. This is the door that does not close.
 */
it('opens an unpublished news item by its own address, with no token', function (): void {
    pendingNews(['by_link' => true]);

    $this->getJson(route('api.v1.news.show', ['news' => 'na-soglasovanii']))
        ->assertOk()
        ->assertJsonPath('data.slug', 'na-soglasovanii')
        ->assertJsonPath('data.content', '<p>Текст</p>');
});

it('still hides an unpublished news item that was not opened', function (): void {
    pendingNews();

    $this->getJson(route('api.v1.news.show', ['news' => 'na-soglasovanii']))->assertNotFound();
});

it('keeps it out of the listing the site and the app read', function (): void {
    pendingNews(['by_link' => true]);

    pendingNews(['slug' => 'zhivaya', 'title' => ['ru' => 'Живая'], 'status' => true]);

    $slugs = $this->getJson(route('api.v1.news.index'))->assertOk()->json('data.*.slug');

    expect($slugs)->toBe(['zhivaya']);
});

/**
 * The publish date is the other way a news item stays off the site, and the
 * link has to reach past it too — that is the case the editors actually have.
 */
it('opens one whose publish date has not arrived yet', function (): void {
    pendingNews(['status' => true, 'published_at' => now()->addWeek(), 'by_link' => true]);

    $this->getJson(route('api.v1.news.show', ['news' => 'na-soglasovanii']))->assertOk();

    expect($this->getJson(route('api.v1.news.index'))->json('data'))->toBe([]);
});

it('tells the site not to let search engines keep it', function (): void {
    pendingNews(['by_link' => true]);

    $this->getJson(route('api.v1.news.show', ['news' => 'na-soglasovanii']))
        ->assertOk()
        ->assertJsonPath('data.by_link', true);
});

it('stops saying so once the news item is published for real', function (): void {
    pendingNews(['by_link' => true, 'status' => true]);

    $this->getJson(route('api.v1.news.show', ['news' => 'na-soglasovanii']))
        ->assertOk()
        ->assertJsonPath('data.by_link', false);
});

/**
 * The handful of people sent the address are approving the text, not reading
 * the site — the same reason a preview link does not count either.
 */
it('does not count the approvers as readers', function (): void {
    $news = pendingNews(['by_link' => true]);

    $this->getJson(route('api.v1.news.show', ['news' => 'na-soglasovanii']))->assertOk();

    expect($news->fresh()->views)->toBe(0);
});

it('counts readers again after it goes live', function (): void {
    $news = pendingNews(['by_link' => true, 'status' => true]);

    $this->getJson(route('api.v1.news.show', ['news' => 'na-soglasovanii']))->assertOk();

    expect($news->fresh()->views)->toBe(1);
});

/**
 * What the editor copies out of the panel is the thing that has to keep
 * working — a token in it would expire a day later in someone's chat.
 */
it('hands the panel a plain address instead of a token link', function (): void {
    $news = pendingNews(['by_link' => true]);

    $url = PreviewAction::make()->record($news)->getUrl();

    expect($url)->toEndWith('/news/na-soglasovanii')
        ->and($url)->not->toContain(PreviewToken::PARAM);
});

it('still issues a token for a draft nobody opened', function (): void {
    $news = pendingNews();

    expect(PreviewAction::make()->record($news)->getUrl())->toContain(PreviewToken::PARAM.'=');
});
