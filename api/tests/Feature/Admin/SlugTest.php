<?php

declare(strict_types=1);

use App\Filament\Resources\News\Pages\CreateNews;
use App\Filament\Resources\News\Pages\EditNews;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();
});

function admin(string $subject): User
{
    $user = panelUser();

    foreach (['ViewAny', 'View', 'Create', 'Update'] as $ability) {
        $user->givePermissionTo(Permission::findOrCreate("{$ability}:{$subject}", 'web'));
    }

    return tap($user->refresh(), fn (User $user) => test()->actingAs($user));
}

/**
 * @return array<string, array<string, string>>
 */
function newsPayload(string $ru): array
{
    return [
        'title' => ['ru' => $ru, 'uz' => $ru],
        'content' => ['ru' => '<p>Текст</p>', 'uz' => '<p>Matn</p>'],
    ];
}

it('builds a slug from the title when the admin leaves it empty', function (): void {
    admin('News');

    Livewire::test(CreateNews::class)
        ->fillForm([...newsPayload('Первая 5G сеть'), 'slug' => ''])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(News::query()->sole()->slug)->toBe('pervaia-5g-set');
});

it('transliterates the russian title when english is empty', function (): void {
    admin('News');

    Livewire::test(CreateNews::class)
        ->fillForm([
            'title' => ['ru' => 'Новости компании', 'uz' => 'Kompaniya yangiliklari'],
            'content' => ['ru' => '<p>Текст</p>', 'uz' => '<p>Matn</p>'],
            'slug' => '',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(News::query()->sole()->slug)->toBe('novosti-kompanii');
});

it('keeps the slug the admin typed', function (): void {
    admin('News');

    Livewire::test(CreateNews::class)
        ->fillForm([...newsPayload('Первая 5G сеть'), 'slug' => 'my-own-address'])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(News::query()->sole()->slug)->toBe('my-own-address');
});

it('never repeats a slug that is already taken', function (): void {
    admin('News');

    News::create([
        'title' => ['ru' => 'Старая'],
        'content' => ['ru' => '<p>x</p>'],
        'slug' => 'pervaia',
        'status' => true,
    ]);

    Livewire::test(CreateNews::class)
        ->fillForm([...newsPayload('Первая'), 'slug' => ''])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(News::query()->orderByDesc('id')->first()->slug)->toBe('pervaia-2');
});

it('regenerates the slug when it is cleared while editing', function (): void {
    admin('News');

    $news = News::create([
        'title' => ['ru' => 'Переименована позже', 'uz' => 'Edi'],
        'content' => ['ru' => '<p>x</p>'],
        'slug' => 'old-address',
        'status' => true,
    ]);

    Livewire::test(EditNews::class, ['record' => $news->getRouteKey()])
        ->fillForm([
            'content' => ['ru' => '<p>x</p>', 'uz' => '<p>x</p>'],
            'slug' => '',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($news->refresh()->slug)->toBe('pereimenovana-pozze');
});
