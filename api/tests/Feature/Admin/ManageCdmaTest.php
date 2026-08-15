<?php

declare(strict_types=1);

use App\Enums\PageKey;
use App\Filament\Pages\ManageCdma;
use App\Models\ContentBlock;
use App\Models\User;
use Database\Seeders\CdmaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();

    $this->admin = User::factory()->create();
    $this->admin->givePermissionTo(Permission::findOrCreate('View:ManageCdma', 'web'));
    $this->admin->refresh();
});

it('seeds every block the cdma manager offers', function (): void {
    $this->seed(CdmaSeeder::class);

    foreach (ManageCdma::tabs() as $tab) {
        expect(ContentBlock::read(PageKey::Cdma, $tab::key()))
            ->not->toBeEmpty("блок {$tab::key()->value} пустой");
    }
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

it('serves the support cards with their icons rendered', function (): void {
    $this->seed(CdmaSeeder::class);

    $cards = $this->getJson(route('api.v1.blocks.show', ['page' => 'cdma']))
        ->assertOk()
        ->json('data.blocks.support.cards');

    expect($cards)->toHaveCount(4)
        ->and($cards[0]['icon_svg'])->toStartWith('<svg')
        ->and(collect($cards)->last()['url'])->toBe('/offices');
});
