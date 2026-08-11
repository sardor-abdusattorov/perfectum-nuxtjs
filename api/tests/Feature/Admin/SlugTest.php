<?php

declare(strict_types=1);

use App\Enums\CategoryType;
use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\News\Pages\CreateNews;
use App\Filament\Resources\News\Pages\EditNews;
use App\Models\Category;
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
    $user = User::factory()->create();

    foreach (['ViewAny', 'View', 'Create', 'Update'] as $ability) {
        $user->givePermissionTo(Permission::findOrCreate("{$ability}:{$subject}", 'web'));
    }

    return tap($user->refresh(), fn (User $user) => test()->actingAs($user));
}

/**
 * @return array<string, array<string, string>>
 */
function newsPayload(string $ru, string $en = ''): array
{
    return [
        'title' => ['ru' => $ru, 'uz' => $ru, 'en' => $en === '' ? $ru : $en],
        'content' => ['ru' => '<p>Текст</p>', 'uz' => '<p>Matn</p>', 'en' => '<p>Text</p>'],
    ];
}

it('builds a slug from the title when the admin leaves it empty', function (): void {
    admin('News');

    Livewire::test(CreateNews::class)
        ->fillForm([...newsPayload('Первая 5G сеть', 'The first 5G network'), 'slug' => ''])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(News::query()->sole()->slug)->toBe('the-first-5g-network');
});

it('transliterates the russian title when english is empty', function (): void {
    admin('News');

    Livewire::test(CreateNews::class)
        ->fillForm([
            'title' => ['ru' => 'Новости компании', 'uz' => 'Kompaniya yangiliklari', 'en' => ''],
            'content' => ['ru' => '<p>Текст</p>', 'uz' => '<p>Matn</p>', 'en' => '<p>Text</p>'],
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
        'slug' => 'the-first-5g-network',
        'status' => true,
    ]);

    Livewire::test(CreateNews::class)
        ->fillForm([...newsPayload('Первая', 'The first 5G network'), 'slug' => ''])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(News::query()->orderByDesc('id')->first()->slug)->toBe('the-first-5g-network-2');
});

it('regenerates the slug when it is cleared while editing', function (): void {
    admin('News');

    $news = News::create([
        'title' => ['ru' => 'Была', 'uz' => 'Edi', 'en' => 'Renamed later'],
        'content' => ['ru' => '<p>x</p>'],
        'slug' => 'old-address',
        'status' => true,
    ]);

    Livewire::test(EditNews::class, ['record' => $news->getRouteKey()])
        ->fillForm([
            'content' => ['ru' => '<p>x</p>', 'uz' => '<p>x</p>', 'en' => ''],
            'slug' => '',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($news->refresh()->slug)->toBe('renamed-later');
});

it('scopes a generated category slug to its type', function (): void {
    admin('Category');

    Category::create([
        'type' => CategoryType::News,
        'name' => ['ru' => 'Акции', 'en' => 'Offers'],
        'slug' => 'offers',
        'status' => true,
    ]);

    Livewire::test(CreateCategory::class)
        ->fillForm([
            'type' => CategoryType::Action->value,
            'name' => ['ru' => 'Акции', 'uz' => 'Aksiyalar', 'en' => 'Offers'],
            'slug' => '',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Category::query()->where('type', CategoryType::Action)->sole()->slug)->toBe('offers');
});
