<?php

declare(strict_types=1);

use App\Models\Social;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        ->get('/admin/socials')
        ->assertOk()
        ->assertSee('Facebook')
        ->assertSee('LinkedIn');
});

it('survives an icon no installed set provides', function (): void {
    Social::create(['name' => 'Broken', 'icon' => 'simple-icons:facebook', 'url' => 'https://example.com', 'sort' => 1]);

    $this->actingAs(socialsAdmin())
        ->get('/admin/socials')
        ->assertOk()
        ->assertSee('Broken');
});

it('offers a fixed list of networks instead of every icon set', function (): void {
    $this->actingAs(socialsAdmin())
        ->get('/admin/socials/create')
        ->assertOk()
        ->assertSee('si-instagram')
        ->assertSee('brand-linkedin')
        ->assertDontSee('heroicon-o-academic-cap');
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
        ->get('/admin/socials/'.$social->getKey().'/edit')
        ->assertOk()
        ->assertSee('si-instagram');
});
