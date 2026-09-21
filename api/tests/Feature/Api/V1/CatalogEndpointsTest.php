<?php

declare(strict_types=1);

use App\Enums\Network;
use App\Models\Action;
use App\Models\ActionCategory;
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
        'slug' => $name,
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

it('serves faqs of one network, keeping the shared ones', function (): void {
    Faq::create([
        'question' => ['ru' => 'Как проверить баланс?'],
        'answer' => ['ru' => '<p>Наберите *100#</p>'],
        'network' => Network::Cdma,
        'pages' => [Faq::PAGE_CDMA],
    ]);
    Faq::create([
        'question' => ['ru' => 'Нужен ли смартфон с 5G?'],
        'answer' => ['ru' => '<p>Да</p>'],
        'network' => Network::FiveG,
        'pages' => [Faq::PAGE_FAQ],
    ]);
    Faq::create([
        'question' => ['ru' => 'Как подключиться?'],
        'answer' => ['ru' => '<p>Через приложение</p>'],
        'pages' => [Faq::PAGE_FAQ],
    ]);

    $this->getJson(route('api.v1.faqs', ['network' => '5g']))
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.question', 'Нужен ли смартфон с 5G?');

    $this->getJson(route('api.v1.faqs', ['network' => 'cdma']))
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.question', 'Как проверить баланс?');

    $this->getJson(route('api.v1.faqs'))->assertOk()->assertJsonCount(3, 'data');

    $this->postJson(route('api.v1.faqs'), ['network' => '5g'])->assertOk()->assertJsonCount(2, 'data');
});

it('narrows faqs by network and page at once', function (): void {
    Faq::create([
        'question' => ['ru' => 'Только поддержка 5G'],
        'answer' => ['ru' => '<p>Да</p>'],
        'network' => Network::FiveG,
        'pages' => [Faq::PAGE_HELP],
    ]);
    Faq::create([
        'question' => ['ru' => 'Только вопросы 5G'],
        'answer' => ['ru' => '<p>Да</p>'],
        'network' => Network::FiveG,
        'pages' => [Faq::PAGE_FAQ],
    ]);

    $this->getJson(route('api.v1.faqs', ['network' => '5g', 'page' => 'help']))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.question', 'Только поддержка 5G');
});

it('leaves a question on both networks when nothing is chosen', function (): void {
    $faq = Faq::create([
        'question' => ['ru' => 'Как подключиться?'],
        'answer' => ['ru' => '<p>Через приложение</p>'],
        'pages' => [Faq::PAGE_FAQ],
    ]);

    expect($faq->fresh()->network)->toBe(Network::Both);
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

it('hands out a slug for every category the site filters by', function (): void {
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

    $slugs = [
        'tariff-categories' => 'domasnii-internet',
        'service-categories' => 'setevye-uslugi',
        'news-categories' => 'kompaniya',
    ];

    foreach ($slugs as $taxonomy => $slug) {
        $this->getJson(route('api.v1.categories', ['taxonomy' => $taxonomy]))
            ->assertOk()
            ->assertJsonPath('data.0.slug', $slug);
    }
});

it('filters a listing by the category slug as well as by its id', function (): void {
    $network = category(NewsCategory::class, Network::Both, 'razvitie-seti');
    news(['category_id' => $network->id, 'slug' => 'v-seti']);
    news(['slug' => 'bez-kategorii']);

    foreach ([$network->slug, (string) $network->id] as $value) {
        $slugs = $this->getJson(route('api.v1.news.index').'?category='.$value)
            ->assertOk()
            ->json('data.*.slug');

        expect($slugs)->toBe(['v-seti']);
    }

    expect($this->getJson(route('api.v1.news.index').'?category=net-takoy')->assertOk()->json('data'))->toBe([]);
});

it('keeps a cdma promo out of the 5g listing', function (): void {
    $promo = fn (array $attributes): Action => Action::create(array_merge([
        'title' => ['ru' => 'Акция', 'uz' => 'Aksiya'],
        'content' => ['ru' => '<p>Текст</p>', 'uz' => '<p>Matn</p>'],
        'status' => true,
    ], $attributes));

    $promo(['slug' => 'internet-bonus', 'network' => Network::Cdma]);
    $promo([
        'slug' => 'letnyaya',
        'network' => Network::FiveG,
        'category_id' => category(ActionCategory::class, Network::Both, 'mobilnaya-svyaz')->id,
    ]);

    $listing = fn (array $query): array => $this->getJson(route('api.v1.actions.index', $query))
        ->assertOk()
        ->json('data.*.slug');

    expect($listing(['network' => '5g']))->toBe(['letnyaya'])
        ->and($listing(['network' => '5g', 'category' => 'mobilnaya-svyaz']))->toBe(['letnyaya'])
        ->and($listing(['network' => 'cdma']))->toBe(['internet-bonus']);
});

/**
 * The app reads a story from the list it already has, so the body travels with
 * it. Every other feed still keeps its body for the card alone.
 */
it('carries the whole news body in the list', function (): void {
    $category = category(NewsCategory::class, Network::Both, 'kompaniya');

    news(['category_id' => $category->id, 'content' => ['ru' => '<p>Полный текст новости</p>']]);

    $this->getJson(route('api.v1.news.index'))
        ->assertOk()
        ->assertJsonPath('data.0.content', '<p>Полный текст новости</p>');
});

it('keeps the body out of the other feeds', function (): void {
    Vacancy::create(['title' => ['ru' => 'Инженер'], 'slug' => 'inzhener', 'content' => ['ru' => '<p>Условия</p>']]);

    $this->getJson(route('api.v1.vacancies.index'))
        ->assertOk()
        ->assertJsonMissingPath('data.0.content');

    $this->getJson(route('api.v1.vacancies.show', 'inzhener'))
        ->assertOk()
        ->assertJsonPath('data.content', '<p>Условия</p>');
});

/**
 * Переводимая колонка лежит в базе целым JSON, и поиск подстрокой по ней
 * отвечал не то: по «ru» совпадал ключ и возвращались все записи, а по
 * узбекскому слову находилась запись, текста которой русскоязычный посетитель
 * не видит.
 */
it('searches inside the language the visitor is reading', function (): void {
    news(['slug' => 'pervaya', 'title' => ['ru' => 'Технические работы', 'uz' => 'Texnik ishlar']]);
    news(['slug' => 'vtoraya', 'title' => ['ru' => 'Новый тариф', 'uz' => 'Yangi tarif']]);

    $found = fn (array $query, array $headers = []) => $this
        ->getJson(route('api.v1.news.index', $query), $headers)
        ->assertOk()
        ->json('data.*.slug');

    expect($found(['search' => 'Технические']))->toBe(['pervaya'])
        ->and($found(['search' => 'Texnik'], ['X-Locale' => 'uz']))->toBe(['pervaya']);
});

it('does not hand back every record for the name of a language', function (): void {
    news(['slug' => 'pervaya']);
    news(['slug' => 'vtoraya']);

    expect($this->getJson(route('api.v1.news.index', ['search' => 'ru']))->assertOk()->json('data'))->toBe([]);
});
