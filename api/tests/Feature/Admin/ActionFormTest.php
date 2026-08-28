<?php

declare(strict_types=1);

use App\Enums\Network;
use App\Filament\Resources\Actions\Pages\CreateAction;
use App\Models\Action;
use App\Models\ActionCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();

    $user = panelUser();

    foreach (['ViewAny', 'View', 'Create', 'Update'] as $ability) {
        $user->givePermissionTo(Permission::findOrCreate("{$ability}:Action", 'web'));
    }

    $this->actingAs($user->refresh());
});

/**
 * @return array<string, mixed>
 */
function promoPayload(array $attributes = []): array
{
    return array_merge([
        'network' => Network::FiveG->value,
        'title' => ['ru' => 'Летняя акция', 'uz' => 'Yozgi aksiya'],
        'content' => ['ru' => '<p>Текст</p>', 'uz' => '<p>Matn</p>'],
    ], $attributes);
}

/**
 * The 5G listing filters by category chips, so a promo without one would show
 * up under «Все» and disappear the moment a visitor picked a chip.
 */
it('asks for a category before a 5g promo is published', function (): void {
    Livewire::test(CreateAction::class)
        ->fillForm(promoPayload())
        ->call('create')
        ->assertHasFormErrors(['category_id' => 'required']);

    $category = ActionCategory::create([
        'name' => ['ru' => 'Мобильная связь'],
        'slug' => 'mobilnaya-svyaz',
        'network' => Network::Both,
    ]);

    Livewire::test(CreateAction::class)
        ->fillForm(promoPayload(['category_id' => $category->getKey()]))
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Action::query()->sole()->category_id)->toBe($category->getKey());
});

/**
 * The CDMA landing has no chips, so those promos carry no category at all —
 * the panel hides the field for them and must not demand it either.
 */
it('does not ask a cdma promo for a category', function (): void {
    Livewire::test(CreateAction::class)
        ->fillForm(promoPayload(['network' => Network::Cdma->value]))
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Action::query()->sole()->category_id)->toBeNull();
});
