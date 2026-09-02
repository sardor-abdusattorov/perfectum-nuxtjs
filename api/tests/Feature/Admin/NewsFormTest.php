<?php

declare(strict_types=1);

use App\Enums\Network;
use App\Filament\Resources\News\Pages\CreateNews;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();

    $user = panelUser();

    foreach (['ViewAny', 'View', 'Create', 'Update'] as $ability) {
        $user->givePermissionTo(Permission::findOrCreate("{$ability}:News", 'web'));
    }

    $this->actingAs($user->refresh());
});

/**
 * @return array<string, mixed>
 */
function newsFormPayload(array $attributes = []): array
{
    return array_merge([
        'network' => Network::FiveG->value,
        'title' => ['ru' => 'Первая 5G сеть', 'uz' => 'Birinchi 5G tarmoq'],
        'content' => ['ru' => '<p>Текст</p>', 'uz' => '<p>Matn</p>'],
    ], $attributes);
}

function newsFormCategory(): NewsCategory
{
    return NewsCategory::create([
        'name' => ['ru' => 'Компания', 'uz' => 'Kompaniya'],
        'slug' => 'kompaniya',
        'network' => Network::Both,
    ]);
}

/**
 * Both feeds filter by category chips — /news for 5G and /cdma/news for CDMA
 * — so the field belongs on the form of either network. It was once hidden for
 * CDMA, which left the editor no way to file a CDMA item under a chip.
 */
it('asks every news item for a category, whichever network it belongs to', function (string $network): void {
    Livewire::test(CreateNews::class)
        ->fillForm(newsFormPayload(['network' => $network]))
        ->call('create')
        ->assertHasFormErrors(['category_id' => 'required']);

    $category = newsFormCategory();

    Livewire::test(CreateNews::class)
        ->fillForm(newsFormPayload(['network' => $network, 'category_id' => $category->getKey()]))
        ->call('create')
        ->assertHasNoFormErrors();

    $news = News::query()->sole();

    expect($news->category_id)->toBe($category->getKey())
        ->and($news->network->value)->toBe($network);
})->with([
    '5g' => [Network::FiveG->value],
    'cdma' => [Network::Cdma->value],
]);

it('shows the category field on the form of a cdma item', function (): void {
    $category = newsFormCategory();

    Livewire::test(CreateNews::class)
        ->fillForm(newsFormPayload(['network' => Network::Cdma->value]))
        ->assertFormFieldVisible('category_id')
        ->fillForm(['category_id' => $category->getKey()])
        ->assertFormSet(['category_id' => $category->getKey()]);
});
