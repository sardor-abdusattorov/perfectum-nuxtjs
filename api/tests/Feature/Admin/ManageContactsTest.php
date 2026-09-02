<?php

declare(strict_types=1);

use App\Enums\ContactCardIcon;
use App\Filament\Pages\ManageContacts;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();

    $this->admin = panelUser(['View:ManageContacts']);
    $this->admin->givePermissionTo(Permission::findOrCreate('View:ManageContacts', 'web'));
    $this->admin->refresh();
});

it('renders one tab at a time and builds the other on demand', function (): void {
    $this->actingAs($this->admin);

    Livewire::test(ManageContacts::class)
        ->assertSee('page_hero.title')
        ->assertDontSee('cards.items')
        ->set('activeTab', 'cards')
        ->assertSee('cards.items');
});

it('offers every card icon rendered from the panel set', function (): void {
    foreach (ContactCardIcon::getIconOptions() as $option) {
        expect($option)->toContain('<svg');
    }
});
