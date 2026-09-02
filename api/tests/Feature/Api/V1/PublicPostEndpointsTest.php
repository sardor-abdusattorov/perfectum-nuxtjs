<?php

declare(strict_types=1);

use App\Enums\Network;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * The site reads these endpoints with GET and a query string; the mobile app
 * sends POST with a JSON body. Both must land on the same rows in the same
 * order, so every case asks twice and compares.
 */
function mobileCategory(string $model, string $slug, string $ru, string $uz): mixed
{
    return $model::create([
        'name' => ['ru' => $ru, 'uz' => $uz],
        'slug' => $slug,
        'network' => Network::Both,
        'sort' => 1,
        'status' => true,
    ]);
}

function mobileNews(string $slug, array $attributes = []): News
{
    return News::create(array_merge([
        'title' => ['ru' => "Новость {$slug}", 'uz' => "Yangilik {$slug}"],
        'slug' => $slug,
        'content' => ['ru' => '<p>Текст</p>', 'uz' => '<p>Matn</p>'],
        'published_at' => now()->subDay(),
        'status' => true,
    ], $attributes));
}

beforeEach(function (): void {
    $this->network = mobileCategory(NewsCategory::class, 'set', 'Сеть', 'Tarmoq');
    $this->company = mobileCategory(NewsCategory::class, 'kompaniya', 'Компания', 'Kompaniya');

    foreach (range(1, 5) as $day) {
        mobileNews("set-{$day}", ['category_id' => $this->network->id, 'published_at' => now()->subDays($day)]);
    }

    mobileNews('kompaniya-1', ['category_id' => $this->company->id]);
    mobileNews('cdma-1', ['network' => Network::Cdma]);
});

it('answers the news feed to a json body the way it answers a query string', function (array $filters): void {
    $get = $this->getJson(route('api.v1.news.index', $filters))->assertOk()->json();
    $post = $this->postJson(route('api.v1.news.index'), $filters)->assertOk()->json();

    expect($post['data'])->toBe($get['data'])
        ->and($post['data'])->not->toBeEmpty()
        ->and($post['meta']['total'])->toBe($get['meta']['total'])
        ->and($post['meta']['current_page'])->toBe($get['meta']['current_page']);
})->with([
    'the whole feed' => [[]],
    'one network' => [['network' => '5g']],
    'one category by slug' => [['category' => 'set']],
    'the second page of two' => [['per_page' => 2, 'page' => 2]],
    'a search' => [['search' => 'kompaniya']],
]);

it('takes a category id as a number in the body', function (): void {
    $this->postJson(route('api.v1.news.index'), ['category' => $this->company->id])
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.slug', 'kompaniya-1');
});

it('reads the language from the json body', function (): void {
    $this->postJson(route('api.v1.news.index'), ['lang' => 'uz', 'category' => 'kompaniya'])
        ->assertOk()
        ->assertJsonPath('data.0.title', 'Yangilik kompaniya-1')
        ->assertJsonPath('data.0.category.name', 'Kompaniya');

    $this->postJson(route('api.v1.news.index'), ['locale' => 'uz', 'lang' => 'ru', 'category' => 'kompaniya'])
        ->assertOk()
        ->assertJsonPath('data.0.title', 'Yangilik kompaniya-1');

    $this->postJson(route('api.v1.news.index'), ['category' => 'kompaniya'], ['X-Locale' => 'uz'])
        ->assertOk()
        ->assertJsonPath('data.0.title', 'Yangilik kompaniya-1');

    $this->postJson(route('api.v1.news.index'), ['lang' => 'en', 'category' => 'kompaniya'])
        ->assertOk()
        ->assertJsonPath('data.0.title', 'Новость kompaniya-1');
});

it('serves one news item to a post with the language in the body', function (): void {
    $get = $this->getJson(route('api.v1.news.show', 'kompaniya-1'))->assertOk()->json('data');
    $post = $this->postJson(route('api.v1.news.show', 'kompaniya-1'))->assertOk()->json('data');

    expect($post)->toBe($get)->and($post)->toHaveKey('content');

    $this->postJson(route('api.v1.news.show', 'kompaniya-1'), ['lang' => 'uz'])
        ->assertOk()
        ->assertJsonPath('data.title', 'Yangilik kompaniya-1');

    $this->postJson(route('api.v1.news.show', 'net-takoi'))->assertNotFound();
});

it('lists categories and questions to a post as it does to a get', function (): void {
    $support = mobileCategory(FaqCategory::class, 'podderzhka', 'Поддержка', 'Yordam');

    Faq::create([
        'question' => ['ru' => 'Как?', 'uz' => 'Qanday?'],
        'answer' => ['ru' => '<p>Так</p>', 'uz' => '<p>Shunday</p>'],
        'pages' => [Faq::PAGE_FAQ],
        'category_id' => $support->id,
    ]);
    Faq::create([
        'question' => ['ru' => 'Где?', 'uz' => 'Qayerda?'],
        'answer' => ['ru' => '<p>Там</p>', 'uz' => '<p>U yerda</p>'],
        'pages' => [Faq::PAGE_HELP],
        'category_id' => $support->id,
    ]);

    $get = $this->getJson(route('api.v1.categories', 'news-categories'))->assertOk()->json('data');
    $post = $this->postJson(route('api.v1.categories', 'news-categories'))->assertOk()->json('data');

    expect($post)->toBe($get)->and($post)->toHaveCount(2);

    $this->postJson(route('api.v1.categories', 'news-categories'), ['lang' => 'uz'])
        ->assertOk()
        ->assertJsonPath('data.0.name', 'Tarmoq');

    $get = $this->getJson(route('api.v1.faqs', ['page' => 'faq', 'category' => 'podderzhka']))->assertOk()->json('data');
    $post = $this->postJson(route('api.v1.faqs'), ['page' => 'faq', 'category' => 'podderzhka'])->assertOk()->json('data');

    expect($post)->toBe($get)
        ->and($post)->toHaveCount(1)
        ->and($post[0]['question'])->toBe('Как?');

    $this->postJson(route('api.v1.faqs'), ['lang' => 'uz'])
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.question', 'Qanday?');
});

/**
 * A query string only ever carries strings; a JSON body can carry a number,
 * an array or an object in the same field. None of those is a filter, and
 * none of them may take the endpoint down.
 */
it('shrugs off a body whose filters have the wrong shape', function (): void {
    $this->postJson(route('api.v1.faqs'), ['page' => 1, 'category' => ['podderzhka']])->assertOk();

    $this->postJson(route('api.v1.news.index'), [
        'category' => ['kompaniya'],
        'network' => ['5g'],
        'per_page' => 'many',
        'page' => ['2'],
        'search' => ['x'],
        'lang' => ['uz'],
    ])
        ->assertOk()
        ->assertJsonCount(7, 'data')
        ->assertJsonPath('data.0.title', fn (string $title): bool => str_starts_with($title, 'Новость'));
});

it('keeps the coverage map on get alone', function (): void {
    $this->postJson(route('api.v1.coverage'))->assertMethodNotAllowed();
    $this->postJson(route('api.v1.coverage.show', '5g'))->assertMethodNotAllowed();
});
