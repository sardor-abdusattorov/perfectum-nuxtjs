<?php

declare(strict_types=1);

use App\Filament\Pages\ManageCdma;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();

    $this->admin = panelUser(['View:ManageCdma']);
    $this->admin->givePermissionTo(Permission::findOrCreate('View:ManageCdma', 'web'));
    $this->admin->refresh();
});

it('renders one tab at a time and builds the others on demand', function (): void {
    $this->actingAs($this->admin);

    Livewire::test(ManageCdma::class)
        ->assertSee('page_hero.title')
        ->assertDontSee('support.cards')
        ->set('activeTab', 'support')
        ->assertSee('support.cards')
        ->set('activeTab', 'cta')
        ->assertSee('cta.title');
});
