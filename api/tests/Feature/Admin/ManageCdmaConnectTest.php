<?php

declare(strict_types=1);

use App\Filament\Pages\ManageCdmaConnect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();

    $this->admin = panelUser(['View:ManageCdmaConnect']);
    $this->admin->givePermissionTo(Permission::findOrCreate('View:ManageCdmaConnect', 'web'));
    $this->admin->refresh();
});

it('renders one tab at a time and builds the other on demand', function (): void {
    $this->actingAs($this->admin);

    Livewire::test(ManageCdmaConnect::class)
        ->assertSee('page_hero.title')
        ->assertDontSee('intro.content')
        ->set('activeTab', 'intro')
        ->assertSee('intro.content');
});
