<?php

declare(strict_types=1);

use App\Filament\Resources\News\Pages\ListNews;
use App\Models\Action;
use App\Models\Device;
use App\Models\News;
use App\Models\Page;
use App\Models\Service;
use App\Models\Tender;
use App\Models\Vacancy;
use App\Support\PreviewToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Cache::flush();
});

function published(string $model): object
{
    return match ($model) {
        News::class => News::create([
            'title' => ['ru' => 'Новость'], 'slug' => 'novost', 'content' => ['ru' => '<p>Текст</p>'],
            'published_at' => now()->subDay(), 'status' => true,
        ]),
        Action::class => Action::create([
            'title' => ['ru' => 'Акция'], 'slug' => 'akciya',
            'content' => ['ru' => '<p>Текст</p>'], 'status' => true,
        ]),
        Service::class => Service::create([
            'name' => ['ru' => 'Услуга'], 'slug' => 'usluga',
            'content' => ['ru' => '<p>Текст</p>'], 'status' => true,
        ]),
        Device::class => Device::create([
            'name' => ['ru' => 'Роутер'], 'slug' => 'router',
            'content' => ['ru' => '<p>Текст</p>'], 'status' => true,
        ]),
        Vacancy::class => Vacancy::create([
            'title' => ['ru' => 'Вакансия'], 'slug' => 'vakansiya',
            'content' => ['ru' => '<p>Текст</p>'], 'status' => true,
        ]),
        Tender::class => Tender::create([
            'title' => ['ru' => 'Закупка'], 'slug' => 'zakupka',
            'content' => ['ru' => '<p>Текст</p>'], 'status' => true,
        ]),
        Page::class => Page::create([
            'title' => ['ru' => 'Страница'], 'slug' => 'stranica',
            'content' => ['ru' => '<p>Текст</p>'], 'status' => true,
        ]),
    };
}

it('counts a reader on every kind of record', function (string $model, string $route): void {
    $record = published($model);

    expect($record->fresh()->views)->toBe(0);

    $this->getJson(route($route, $record->slug))->assertOk();

    expect($record->fresh()->views)->toBe(1);
})->with([
    'новость' => [News::class, 'api.v1.news.show'],
    'акция' => [Action::class, 'api.v1.actions.show'],
    'услуга' => [Service::class, 'api.v1.services.show'],
    'устройство' => [Device::class, 'api.v1.devices.show'],
    'вакансия' => [Vacancy::class, 'api.v1.vacancies.show'],
    'закупка' => [Tender::class, 'api.v1.tenders.show'],
    'страница' => [Page::class, 'api.v1.pages.show'],
]);

/**
 * Server rendering asks the API for the record on the first paint and the
 * browser asks again when the visitor navigates back to it, so one reader has
 * to stay one view.
 */
it('counts one reader once however many times the page is fetched', function (): void {
    $news = published(News::class);

    foreach (range(1, 4) as $ignored) {
        $this->getJson(route('api.v1.news.show', ['news' => 'novost']))->assertOk();
    }

    expect($news->fresh()->views)->toBe(1);
});

it('counts a second reader separately', function (): void {
    $news = published(News::class);

    $this->getJson(route('api.v1.news.show', ['news' => 'novost']))->assertOk();

    $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.7'])
        ->getJson(route('api.v1.news.show', ['news' => 'novost']))->assertOk();

    expect($news->fresh()->views)->toBe(2);
});

/**
 * An editor opening their own draft through a preview link is checking their
 * work, not reading the site.
 */
it('does not count an editor looking at a draft', function (): void {
    $news = News::create([
        'title' => ['ru' => 'Черновик'], 'slug' => 'chernovik', 'content' => ['ru' => '<p>Текст</p>'],
        'published_at' => now()->subDay(), 'status' => false,
    ]);

    $this->getJson(route('api.v1.news.show', ['news' => 'chernovik']).'?'.PreviewToken::PARAM.'='.PreviewToken::for($news))
        ->assertOk();

    expect($news->fresh()->views)->toBe(0);
});

it('keeps the counter out of what the site is handed', function (): void {
    published(News::class);

    $this->getJson(route('api.v1.news.show', ['news' => 'novost']))
        ->assertOk()
        ->assertJsonMissingPath('data.views');
});

it('shows the counter in the admin list and lets it be sorted', function (): void {
    $quiet = published(News::class);

    $loud = News::create([
        'title' => ['ru' => 'Популярная'], 'slug' => 'populyarnaya', 'content' => ['ru' => '<p>Текст</p>'],
        'published_at' => now()->subDay(), 'status' => true,
    ]);

    $loud->forceFill(['views' => 42])->saveQuietly();

    $this->withoutVite();
    $this->actingAs(panelUser(['ViewAny:News', 'View:News']));

    Livewire\Livewire::test(ListNews::class)
        ->assertTableColumnExists('views')
        ->sortTable('views', 'desc')
        ->assertCanSeeTableRecords([$loud, $quiet], inOrder: true);
});
