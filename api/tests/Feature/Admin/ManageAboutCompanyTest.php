<?php

declare(strict_types=1);

use App\Filament\Pages\ManageAboutCompany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();

    $this->admin = panelUser(['View:ManageAboutCompany']);
    $this->admin->givePermissionTo(Permission::findOrCreate('View:ManageAboutCompany', 'web'));
    $this->admin->refresh();
});

it('renders one tab at a time and builds the rest on demand', function (): void {
    $this->actingAs($this->admin);

    $page = Livewire::test(ManageAboutCompany::class)
        ->assertSee('page_hero.title')
        ->assertDontSee('stats.items');

    $page->set('activeTab', 'stats')->assertSee('stats.items');
    $page->set('activeTab', 'intro')->assertSee('intro.content');
    $page->set('activeTab', 'timeline')->assertSee('timeline.items');
});
