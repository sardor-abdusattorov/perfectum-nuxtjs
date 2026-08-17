<?php

declare(strict_types=1);

use App\Filament\Pages\ManageCdma;
use App\Filament\Pages\ManageContacts;
use App\Filament\Pages\ManageHomepage;
use App\Filament\Support\Fields;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

function newsItem(array $attributes = []): News
{
    return News::create(array_merge([
        'title' => ['ru' => 'Заголовок', 'uz' => 'Sarlavha'],
        'slug' => 'zagolovok',
        'content' => ['ru' => '<p>Текст</p>', 'uz' => '<p>Matn</p>'],
        'published_at' => now()->subDay(),
        'status' => true,
    ], $attributes));
}

it('keeps an embargoed news item off its own page, not just off the feed', function (): void {
    newsItem(['published_at' => now()->addWeek()]);

    $this->getJson(route('api.v1.news.index'))->assertOk()->assertJsonCount(0, 'data');
    $this->getJson(route('api.v1.news.show', ['news' => 'zagolovok']))->assertNotFound();
});

it('serves a news item once its publication moment has passed', function (): void {
    newsItem();

    $this->getJson(route('api.v1.news.show', ['news' => 'zagolovok']))->assertOk();
});

it('treats a news item with no publication date as unpublished', function (): void {
    newsItem(['published_at' => null]);

    $this->getJson(route('api.v1.news.show', ['news' => 'zagolovok']))->assertNotFound();
});

it('names the types of the shared upload helper', function (): void {
    expect(Fields::file('documents')->getAcceptedFileTypes())->toBe(Fields::DOCUMENT_TYPES)
        ->and(Fields::image('news')->getAcceptedFileTypes())->toBe(['image/*']);
});

/**
 * The public disk is the web root, so an upload field that names no types is
 * how a .php file gets in — every raw field must restrict itself the way the
 * shared helpers already do.
 */
it('restricts the file types of every upload field in the admin', function (string $file): void {
    $source = (string) file_get_contents($file);
    $fields = substr_count($source, 'FileUpload::make(');
    $restricted = substr_count($source, '->acceptedFileTypes(') + substr_count($source, '->image()');

    expect($restricted)->toBeGreaterThanOrEqual($fields);
})->with(function (): array {
    $files = array_merge(
        glob(__DIR__.'/../../app/Filament/*/*/Schemas/*.php') ?: [],
        glob(__DIR__.'/../../app/Filament/Pages/*.php') ?: [],
        glob(__DIR__.'/../../app/Filament/Pages/*/*.php') ?: [],
        glob(__DIR__.'/../../app/Filament/Support/*.php') ?: [],
    );

    return array_values(array_filter(
        $files,
        fn (string $file): bool => str_contains((string) file_get_contents($file), 'FileUpload::make('),
    ));
});

it('escapes a search term so its wildcards cannot scan the table', function (): void {
    newsItem(['slug' => 'pervaya', 'title' => ['ru' => 'Первая новость']]);
    newsItem(['slug' => 'vtoraya', 'title' => ['ru' => 'Вторая новость']]);

    $this->getJson(route('api.v1.news.index', ['search' => '%']))
        ->assertOk()
        ->assertJsonCount(0, 'data');
});

it('shuts a user with no role out of the panel', function (): void {
    $stranger = User::factory()->create();
    $admin = User::factory()->create();
    $admin->assignRole(Role::findOrCreate('super_admin', 'web'));

    $panel = Filament\Facades\Filament::getPanel('admin');

    expect($stranger->canAccessPanel($panel))->toBeFalse()
        ->and($admin->canAccessPanel($panel))->toBeTrue();
});

it('guards the block editors behind their own permission', function (): void {
    $this->actingAs(User::factory()->create());

    expect(ManageHomepage::canAccess())->toBeFalse();
});

/**
 * The shield trait keeps the resolved permission in a static, so mounting it on
 * the shared base class let whichever editor was checked first answer for all
 * six of them.
 */
it('does not let one block editor answer for another', function (): void {
    $this->actingAs(panelUser(['View:ManageCdma']));

    expect(ManageCdma::canAccess())->toBeTrue()
        ->and(ManageHomepage::canAccess())->toBeFalse()
        ->and(ManageContacts::canAccess())->toBeFalse();
});

/**
 * Server-side rendering funnels every visitor through the frontend host, so the
 * read budget has to be generous while the calls that reach a paid upstream
 * stay tight.
 */
it('throttles the reads generously and the upstream proxies tightly', function (): void {
    expect($this->getJson(route('api.v1.news.index'))->headers->get('X-RateLimit-Limit'))->toBe('600');

    $this->postJson(route('api.v1.numbers'), ['sku' => 'x']);

    expect(RateLimiter::limiter('upstream')(request())->maxAttempts)->toBe(30);
});
