<?php

declare(strict_types=1);

use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Models\ContentBlock;
use App\Models\User;
use App\Support\Content;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();
});

function homepageAdmin(): User
{
    $user = User::factory()->create();

    $user->givePermissionTo(Permission::findOrCreate('View:ManageHomepage', 'web'));

    return $user->refresh();
}

it('renders every tab of the homepage manager', function (): void {
    $this->actingAs(homepageAdmin())
        ->get('/admin/homepage')
        ->assertOk()
        ->assertSee('hero.slides')
        ->assertSee('choose.cards')
        ->assertSee('app_promo.watermark');
});

it('offers buttons and both switches inside the hero slide', function (): void {
    Content::save(PageKey::Home, ContentBlockKey::Hero, [
        'slides' => [
            [
                'title' => ['ru' => 'Скорость'],
                'buttons' => [['label' => ['ru' => 'Подключиться'], 'url' => '/tariffs', 'style' => 'primary']],
                'show_aside' => true,
                'show_gauge' => true,
                'status' => true,
            ],
        ],
    ]);

    $this->actingAs(homepageAdmin())
        ->get('/admin/homepage')
        ->assertOk()
        ->assertSee('show_aside')
        ->assertSee('show_gauge')
        ->assertSee('slide_translations')
        ->assertSee('button_translations');
});

it('keeps buttons and the two hero switches inside a slide', function (): void {
    Content::save(PageKey::Home, ContentBlockKey::Hero, [
        'slides' => [
            [
                'title' => ['ru' => 'Скорость'],
                'lead' => ['ru' => 'Запуск 5G'],
                'buttons' => [
                    ['label' => ['ru' => 'Подключиться'], 'url' => '/tariffs', 'style' => 'primary', 'status' => true],
                ],
                'show_aside' => false,
                'show_gauge' => true,
                'status' => true,
            ],
        ],
    ]);

    $slide = Content::get(PageKey::Home, ContentBlockKey::Hero)['slides'][0];

    expect($slide['buttons'])->toHaveCount(1)
        ->and($slide['buttons'][0]['url'])->toBe('/tariffs')
        ->and($slide['show_aside'])->toBeFalse()
        ->and($slide['show_gauge'])->toBeTrue();

    expect(ContentBlock::query()->where('key', ContentBlockKey::Hero)->exists())->toBeTrue();
});
