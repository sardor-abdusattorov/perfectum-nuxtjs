<?php

declare(strict_types=1);

use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Filament\Resources\Pages\Pages\EditPage;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();

    $user = panelUser();

    foreach (['ViewAny', 'View', 'Create', 'Update'] as $ability) {
        $user->givePermissionTo(Permission::findOrCreate("{$ability}:Page", 'web'));
    }

    $this->actingAs($user->refresh());
});

function cardPage(string $slug, array $attributes = []): Page
{
    return Page::create(array_merge([
        'slug' => $slug,
        'title' => ['ru' => ucfirst($slug), 'uz' => ucfirst($slug)],
        'content' => ['ru' => '<p>Текст</p>', 'uz' => '<p>Matn</p>'],
        'status' => true,
    ], $attributes));
}

it('lets a page be nothing but a list of cards', function (): void {
    Livewire::test(CreatePage::class)
        ->fillForm([
            'title' => ['ru' => 'Полезно знать', 'uz' => 'Bilish foydali'],
            'slug' => 'polezno-znat',
            'is_group' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Page::query()->where('slug', 'polezno-znat')->exists())->toBeTrue();
});

it('collects the chosen pages onto the hub', function (): void {
    $hub = cardPage('polezno-znat', ['is_group' => true]);
    $first = cardPage('tarify');
    $second = cardPage('oplata');

    Livewire::test(EditPage::class, ['record' => $hub->getRouteKey()])
        ->fillForm(['children' => [$first->getKey(), $second->getKey()]])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($first->fresh()->parent_id)->toBe($hub->getKey())
        ->and($second->fresh()->parent_id)->toBe($hub->getKey())
        ->and($hub->children()->pluck('slug')->all())->toBe(['tarify', 'oplata']);
});

it('takes a page off the hub when it is unpicked', function (): void {
    $hub = cardPage('polezno-znat', ['is_group' => true]);
    $kept = cardPage('tarify', ['parent_id' => $hub->getKey()]);
    $removed = cardPage('oplata', ['parent_id' => $hub->getKey()]);

    Livewire::test(EditPage::class, ['record' => $hub->getRouteKey()])
        ->fillForm(['children' => [$kept->getKey()]])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($kept->fresh()->parent_id)->toBe($hub->getKey())
        ->and($removed->fresh()->parent_id)->toBeNull();
});

it('does not offer the hub itself as one of its own cards', function (): void {
    $hub = cardPage('polezno-znat', ['is_group' => true]);
    cardPage('tarify');

    $options = Livewire::test(EditPage::class, ['record' => $hub->getRouteKey()])
        ->instance()
        ->form
        ->getComponent('children')
        ->getOptions();

    expect($options)->not->toHaveKey($hub->getKey())
        ->and(array_values($options))->toBe(['Tarify']);
});

it('leaves the cards behind when the hub is deleted', function (): void {
    $hub = cardPage('polezno-znat', ['is_group' => true]);
    $card = cardPage('tarify', ['parent_id' => $hub->getKey()]);

    $hub->delete();

    expect($card->fresh())->not->toBeNull()
        ->and($card->fresh()->parent_id)->toBeNull();
});
