<?php

declare(strict_types=1);

use App\Enums\Network;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\ServiceCategory;
use App\Models\TariffCategory;
use App\Models\Vacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function category(string $model, Network $network, string $name): mixed
{
    return $model::create([
        'name' => ['ru' => $name, 'uz' => $name],
        'network' => $network,
        'sort' => 1,
        'status' => true,
    ]);
}

function news(array $attributes = []): News
{
    return News::create(array_merge([
        'title' => ['ru' => 'Заголовок', 'uz' => 'Sarlavha'],
        'slug' => 'zagolovok',
        'content' => ['ru' => '<p>Текст</p>', 'uz' => '<p>Matn</p>'],
        'published_at' => now()->subDay(),
        'status' => true,
    ], $attributes));
}

it('splits the news feed by the record network', function (): void {
    news();
    news(['slug' => 'cdma-novost', 'network' => Network::Cdma]);

    $this->getJson(route('api.v1.news.index', ['network' => 'cdma']))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.slug', 'cdma-novost');

    $this->getJson(route('api.v1.news.index', ['network' => '5g']))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.slug', 'zagolovok');
});

it('filters the list by category', function (): void {
    news(['category_id' => category(NewsCategory::class, Network::Both, 'razvitie')->id]);
    $company = category(NewsCategory::class, Network::Both, 'kompaniya');
    news(['slug' => 'vtoraya', 'category_id' => $company->id]);

    $this->getJson(route('api.v1.news.index', ['category' => $company->id]))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.slug', 'vtoraya');
});

it('hides a record scheduled for the future', function (): void {
    news(['published_at' => now()->addWeek()]);

    $this->getJson(route('api.v1.news.index'))
        ->assertOk()
        ->assertJsonCount(0, 'data');
});

it('puts a featured record first', function (): void {
    news(['slug' => 'obychnaya', 'published_at' => now()]);
    news(['slug' => 'glavnaya', 'is_featured' => true, 'published_at' => now()->subWeek()]);

    $this->getJson(route('api.v1.news.index'))
        ->assertOk()
        ->assertJsonPath('data.0.slug', 'glavnaya');
});

it('serves a record by slug in the requested locale', function (): void {
    news();

    $this->getJson(route('api.v1.news.show', ['news' => 'zagolovok']), ['X-Locale' => 'uz'])
        ->assertOk()
        ->assertJsonPath('data.title', 'Sarlavha');
});

it('returns not found for an unpublished record', function (): void {
    news(['status' => false]);

    $this->getJson(route('api.v1.news.show', ['news' => 'zagolovok']))->assertNotFound();
});

it('lists categories of one type only', function (): void {
    category(NewsCategory::class, Network::Both, 'kompaniya');
    category(FaqCategory::class, Network::Both, 'podklyuchenie');

    $this->getJson(route('api.v1.categories', ['taxonomy' => 'news-categories']))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'kompaniya');
});

it('rejects an unknown category type', function (): void {
    $this->getJson('/api/v1/categories/not-a-type')->assertNotFound();
});

it('serves faqs of the requested page only', function (): void {
    Faq::create([
        'question' => ['ru' => 'Как проверить баланс?'],
        'answer' => ['ru' => '<p>Наберите *100#</p>'],
        'pages' => [Faq::PAGE_CDMA],
    ]);
    Faq::create([
        'question' => ['ru' => 'Как подключиться?'],
        'answer' => ['ru' => '<p>Через приложение</p>'],
        'pages' => [Faq::PAGE_FAQ, Faq::PAGE_HELP],
    ]);

    $this->getJson(route('api.v1.faqs', ['page' => 'cdma']))->assertOk()->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.question', 'Как проверить баланс?');
    $this->getJson(route('api.v1.faqs', ['page' => 'help']))->assertOk()->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.question', 'Как подключиться?');
    $this->getJson(route('api.v1.faqs', ['page' => 'faq']))->assertOk()->assertJsonCount(1, 'data');
    $this->getJson(route('api.v1.faqs'))->assertOk()->assertJsonCount(2, 'data');
});

it('paginates a long list', function (): void {
    foreach (range(1, 15) as $index) {
        news(['slug' => "novost-{$index}"]);
    }

    $this->getJson(route('api.v1.news.index', ['per_page' => 5]))
        ->assertOk()
        ->assertJsonCount(5, 'data')
        ->assertJsonPath('meta.total', 15)
        ->assertJsonPath('meta.last_page', 3);
});

it('caps an oversized page size', function (): void {
    Vacancy::create(['title' => ['ru' => 'Инженер'], 'slug' => 'inzhener', 'content' => ['ru' => '<p>x</p>']]);

    $this->getJson(route('api.v1.vacancies.index', ['per_page' => 500]))
        ->assertOk()
        ->assertJsonPath('meta.per_page', 100);
});

it('serves a taxonomy from cache and drops it when a category changes', function (): void {
    $company = category(NewsCategory::class, Network::Both, 'kompaniya');

    $this->getJson(route('api.v1.categories', ['taxonomy' => 'news-categories']))
        ->assertOk()
        ->assertJsonPath('data.0.name', 'kompaniya');

    $company->update(['name' => ['ru' => 'novoe-imya', 'uz' => 'novoe-imya']]);

    $this->getJson(route('api.v1.categories', ['taxonomy' => 'news-categories']))
        ->assertOk()
        ->assertJsonPath('data.0.name', 'novoe-imya');
});

/**
 * The open category of a listing lives in the address as its slug, so the
 * categories that have one must hand it out — and the ones that never got a
 * slug column must not pretend to.
 */
it('hands out the category slug where the table keeps one', function (): void {
    TariffCategory::create([
        'name' => ['ru' => 'Домашний интернет', 'uz' => 'Uy internet'],
        'slug' => 'domasnii-internet',
        'network' => Network::FiveG,
        'sort' => 1,
        'status' => true,
    ]);

    ServiceCategory::create([
        'name' => ['ru' => 'Сетевые услуги', 'uz' => 'Tarmoq xizmatlari'],
        'slug' => 'setevye-uslugi',
        'network' => Network::FiveG,
        'sort' => 1,
        'status' => true,
    ]);

    category(NewsCategory::class, Network::Both, 'kompaniya');

    $this->getJson(route('api.v1.categories', ['taxonomy' => 'tariff-categories']))
        ->assertOk()
        ->assertJsonPath('data.0.slug', 'domasnii-internet');

    $this->getJson(route('api.v1.categories', ['taxonomy' => 'service-categories']))
        ->assertOk()
        ->assertJsonPath('data.0.slug', 'setevye-uslugi');

    $this->getJson(route('api.v1.categories', ['taxonomy' => 'news-categories']))
        ->assertOk()
        ->assertJsonPath('data.0.slug', null);
});
