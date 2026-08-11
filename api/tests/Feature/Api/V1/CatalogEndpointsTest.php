<?php

declare(strict_types=1);

use App\Enums\CategoryType;
use App\Enums\Network;
use App\Models\Category;
use App\Models\Faq;
use App\Models\News;
use App\Models\Vacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function category(CategoryType $type, Network $network, string $slug): Category
{
    return Category::create([
        'type' => $type,
        'network' => $network,
        'name' => ['ru' => ucfirst($slug)],
        'slug' => $slug,
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

it('keeps a five g record out of the cdma section', function (): void {
    news(['category_id' => category(CategoryType::News, Network::FiveG, 'razvitie')->id]);

    $this->getJson(route('api.v1.news.index', ['network' => 'cdma']))
        ->assertOk()
        ->assertJsonCount(0, 'data');

    $this->getJson(route('api.v1.news.index', ['network' => '5g']))
        ->assertOk()
        ->assertJsonCount(1, 'data');
});

it('shows a shared record in both sections', function (): void {
    news(['category_id' => category(CategoryType::News, Network::Both, 'kompaniya')->id]);

    foreach (['5g', 'cdma'] as $network) {
        $this->getJson(route('api.v1.news.index', ['network' => $network]))
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }
});

it('keeps a record without a category in every section', function (): void {
    news();

    $this->getJson(route('api.v1.news.index', ['network' => 'cdma']))
        ->assertOk()
        ->assertJsonCount(1, 'data');
});

it('filters by category slug', function (): void {
    news(['category_id' => category(CategoryType::News, Network::Both, 'razvitie')->id]);
    news(['slug' => 'vtoraya', 'category_id' => category(CategoryType::News, Network::Both, 'kompaniya')->id]);

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
    category(CategoryType::News, Network::Both, 'kompaniya');
    category(CategoryType::Faq, Network::Both, 'podklyuchenie');

    $this->getJson(route('api.v1.categories', ['type' => 'news']))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.slug', 'kompaniya');
});

it('rejects an unknown category type', function (): void {
    $this->getJson('/api/v1/categories/not-a-type')->assertNotFound();
});

it('serves faqs of the requested network', function (): void {
    Faq::create([
        'category_id' => category(CategoryType::Faq, Network::Cdma, 'cdma-help')->id,
        'question' => ['ru' => 'Как проверить баланс?'],
        'answer' => ['ru' => '<p>Наберите *100#</p>'],
    ]);

    $this->getJson(route('api.v1.faqs', ['network' => 'cdma']))->assertOk()->assertJsonCount(1, 'data');
    $this->getJson(route('api.v1.faqs', ['network' => '5g']))->assertOk()->assertJsonCount(0, 'data');
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
