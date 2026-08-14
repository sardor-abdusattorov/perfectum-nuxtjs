<?php

declare(strict_types=1);

use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Filament\Pages\ManageCdmaConnect;
use App\Models\ContentBlock;
use App\Models\User;
use Database\Seeders\CdmaConnectSeeder;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();

    $this->admin = User::factory()->create();
    $this->admin->givePermissionTo(Permission::findOrCreate('View:ManageCdmaConnect', 'web'));
    $this->admin->refresh();
});

it('seeds every block the cdma connect manager offers', function (): void {
    $this->seed(CdmaConnectSeeder::class);

    foreach (ManageCdmaConnect::tabs() as $tab) {
        expect(ContentBlock::read(PageKey::CdmaConnect, $tab::key()))
            ->not->toBeEmpty("блок {$tab::key()->value} пустой");
    }
});

it('renders both tabs of the cdma connect manager', function (): void {
    $this->actingAs($this->admin)
        ->get('/admin/cdma-connect')
        ->assertOk()
        ->assertSee('page_hero.title')
        ->assertSee('intro.content');
});

it('carries the office tables over as plain html', function (): void {
    $this->seed(CdmaConnectSeeder::class);

    $content = ContentBlock::read(PageKey::CdmaConnect, ContentBlockKey::Intro)['content']['uz'];

    expect(substr_count($content, '<table>'))->toBe(6);
    expect($content)->toContain('Toshkent shahridagi ofislar');
    expect($content)->not->toContain('\\"');
});

/**
 * The page arrived in Uzbek only, so the required Russian title is empty on
 * the neighbouring tab — saving the article must not care.
 */
it('saves one tab while another tab misses a required field', function (): void {
    $this->seed(CdmaConnectSeeder::class);

    expect(ContentBlock::read(PageKey::CdmaConnect, ContentBlockKey::PageHero)['title'])
        ->not->toHaveKey('ru');

    $this->actingAs($this->admin);

    Livewire::test(ManageCdmaConnect::class)
        ->set('data.intro.date.uz', '2 Fevral, 2026')
        ->callAction(TestAction::make('save_intro')->schemaComponent(true))
        ->assertHasNoErrors();

    expect(ContentBlock::read(PageKey::CdmaConnect, ContentBlockKey::Intro)['date']['uz'])
        ->toBe('2 Fevral, 2026');
});

it('writes the article back through its save action', function (): void {
    $this->seed(CdmaConnectSeeder::class);

    $this->actingAs($this->admin);

    Livewire::test(ManageCdmaConnect::class)
        ->set('data.intro.title.uz', 'Yangi sarlavha')
        ->callAction(TestAction::make('save_intro')->schemaComponent(true));

    expect(ContentBlock::read(PageKey::CdmaConnect, ContentBlockKey::Intro)['title']['uz'])
        ->toBe('Yangi sarlavha');
});
