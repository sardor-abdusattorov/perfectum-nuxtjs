<?php

declare(strict_types=1);

use App\Filament\Resources\Socials\Pages\CreateSocial;
use App\Models\Social;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();
});

function socialsAdmin(): User
{
    $user = panelUser();

    foreach (['ViewAny:Social', 'View:Social', 'Create:Social', 'Update:Social'] as $permission) {
        $user->givePermissionTo(Permission::findOrCreate($permission, 'web'));
    }

    return $user->refresh();
}

it('lists the networks with their icons', function (): void {
    Social::create(['name' => 'Facebook', 'icon' => 'si-facebook', 'url' => 'https://facebook.com/x', 'sort' => 1]);
    Social::create(['name' => 'LinkedIn', 'icon' => 'brand-linkedin', 'url' => 'https://linkedin.com/x', 'sort' => 2]);

    $this->actingAs(socialsAdmin())
        ->get(panel('/socials'))
        ->assertOk()
        ->assertSee('Facebook')
        ->assertSee('LinkedIn');
});

it('survives an icon no installed set provides', function (): void {
    Social::create(['name' => 'Broken', 'icon' => 'simple-icons:facebook', 'url' => 'https://example.com', 'sort' => 1]);

    $this->actingAs(socialsAdmin())
        ->get(panel('/socials'))
        ->assertOk()
        ->assertSee('Broken');
});

it('offers a fixed list of networks instead of every icon set', function (): void {
    $this->actingAs(socialsAdmin())
        ->get(panel('/socials/create'))
        ->assertOk()
        ->assertSee('si-instagram')
        ->assertSee('brand-linkedin')
        ->assertDontSee('heroicon-o-academic-cap');
});

/**
 * The column is varchar(255). MySQL refuses a longer value with «Data too
 * long» — a 500 in the admin's face — while SQLite stores it, so the form has
 * to be the one that says no.
 */
it('refuses a url longer than the column instead of letting the database throw', function (): void {
    $this->actingAs(socialsAdmin());

    Livewire::test(CreateSocial::class)
        ->fillForm([
            'name' => 'Telegram',
            'icon' => 'si-telegram',
            'url' => 'https://example.com/'.str_repeat('a', 300),
            'sort' => 1,
        ])
        ->call('create')
        ->assertHasFormErrors(['url' => 'max']);

    expect(Social::query()->count())->toBe(0);
});

it('shows an old iconify value as the matching option', function (): void {
    $social = Social::create([
        'name' => 'Instagram',
        'icon' => 'simple-icons:instagram',
        'url' => 'https://instagram.com/x',
        'sort' => 1,
    ]);

    Social::withoutEvents(fn () => $social->newQuery()->whereKey($social->getKey())
        ->update(['icon' => 'simple-icons:instagram']));

    $this->actingAs(socialsAdmin())
        ->get(panel('/socials/'.$social->getKey().'/edit'))
        ->assertOk()
        ->assertSee('si-instagram');
});
