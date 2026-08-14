<?php

declare(strict_types=1);

use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Filament\Pages\ManageAboutCompany;
use App\Models\ContentBlock;
use App\Models\User;
use Database\Seeders\AboutCompanySeeder;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();

    $this->admin = User::factory()->create();
    $this->admin->givePermissionTo(Permission::findOrCreate('View:ManageAboutCompany', 'web'));
    $this->admin->refresh();
});

it('seeds every block the about company manager offers', function (): void {
    $this->seed(AboutCompanySeeder::class);

    foreach (ManageAboutCompany::tabs() as $tab) {
        expect(ContentBlock::read(PageKey::AboutCompany, $tab::key()))
            ->not->toBeEmpty("блок {$tab::key()->value} пустой");
    }
});

it('renders every tab of the about company manager', function (): void {
    $this->actingAs($this->admin)
        ->get('/admin/about-company')
        ->assertOk()
        ->assertSee('page_hero.title')
        ->assertSee('stats.items')
        ->assertSee('intro.content')
        ->assertSee('timeline.items');
});

it('loads the seeded blocks into the form', function (): void {
    $this->seed(AboutCompanySeeder::class);

    $this->actingAs($this->admin);

    $state = Livewire::test(ManageAboutCompany::class)->get('data');

    expect($state['page_hero']['eyebrow']['ru'])->toBe('Компания');
    expect($state['stats']['items'])->toHaveCount(4);
    expect($state['timeline']['items'])->toHaveCount(4);
});

it('writes a block back through its save action', function (): void {
    $this->seed(AboutCompanySeeder::class);

    $this->actingAs($this->admin);

    Livewire::test(ManageAboutCompany::class)
        ->set('data.page_hero.title', ['ru' => 'Новый заголовок', 'uz' => 'Yangi sarlavha'])
        ->callAction(TestAction::make('save_page_hero')->schemaComponent(true));

    $title = ContentBlock::read(PageKey::AboutCompany, ContentBlockKey::PageHero)['title'];

    expect($title['ru'])->toContain('Новый заголовок');
    expect($title['uz'])->toContain('Yangi sarlavha');
});
