<?php

declare(strict_types=1);

use App\Enums\Network;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Vacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function category(string $model, Network $network, string $slug): mixed
{
    return $model::create([
        'name' => ['ru' => $slug, 'uz' => $slug],
        'slug' => $slug,
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

it('filters by category slug', function (): void {
    news(['category_id' => category(NewsCategory::class, Network::Both, 'razvitie')->id]);
    news(['slug' => 'vtoraya', 'category_id' => category(NewsCategory::class, Network::Both, 'kompaniya')->id]);

    $this->getJson(route('api.v1.news.index', ['category' => 'kompaniya']))
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
        ->assertJsonPath('data.0.slug', 'kompaniya');
});

it('rejects an unknown category type', function (): void {
    $this->getJson('/api/v1/categories/not-a-type')->assertNotFound();
});

it('serves faqs of the requested network', function (): void {
    Faq::create([
        'category_id' => category(FaqCategory::class, Network::Cdma, 'cdma-help')->id,
        'question' => ['ru' => 'Как проверить баланс?'],
        'answer' => ['ru' => '<p>Наберите *100#</p>'],
    ]);

    $this->getJson(route('api.v1.faqs', ['network' => 'cdma']))->assertOk()->assertJsonCount(1, 'data');
    $this->getJson(route('api.v1.faqs', ['network' => '5g']))->assertOk()->assertJsonCount(0, 'data');
});

it('serves only featured faqs when asked', function (): void {
    Faq::create(['question' => ['ru' => 'Обычный'], 'answer' => ['ru' => '<p>a</p>'], 'is_featured' => false]);
    Faq::create(['question' => ['ru' => 'Популярный'], 'answer' => ['ru' => '<p>b</p>'], 'is_featured' => true]);

    $this->getJson(route('api.v1.faqs', ['featured' => 1]))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.question', 'Популярный');
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
        ->assertJsonPath('meta.per_page', 48);
});
