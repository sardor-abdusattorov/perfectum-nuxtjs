<?php

declare(strict_types=1);

use App\Enums\ContactCard;
use App\Enums\ContactCardIcon;
use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Filament\Pages\ManageContacts;
use App\Models\ContentBlock;
use Database\Seeders\ContactsSeeder;
use Filament\Actions\Testing\TestAction;
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

it('seeds every block the contacts manager offers', function (): void {
    $this->seed(ContactsSeeder::class);

    foreach (ManageContacts::tabs() as $tab) {
        expect(ContentBlock::read(PageKey::Contacts, $tab::key()))
            ->not->toBeEmpty("блок {$tab::key()->value} пустой");
    }
});

it('renders one tab at a time and builds the other on demand', function (): void {
    $this->actingAs($this->admin);

    Livewire::test(ManageContacts::class)
        ->assertSee('page_hero.title')
        ->assertDontSee('cards.items')
        ->set('activeTab', 'cards')
        ->assertSee('cards.items');
});

it('seeds one card per contact type', function (): void {
    $this->seed(ContactsSeeder::class);

    $types = collect(ContentBlock::read(PageKey::Contacts, ContentBlockKey::Cards)['items'])
        ->pluck('type')
        ->all();

    expect($types)->toBe(array_column(ContactCard::cases(), 'value'));
});

it('keeps the picked icon and serves it rendered', function (): void {
    $this->seed(ContactsSeeder::class);

    $this->actingAs($this->admin);

    $component = Livewire::test(ManageContacts::class);

    $first = array_key_first($component->get('data')['cards']['items']);

    $component
        ->set("data.cards.items.{$first}.icon", ContactCardIcon::Lifebuoy->value)
        ->callAction(TestAction::make('save_cards')->schemaComponent(true));

    expect(ContentBlock::read(PageKey::Contacts, ContentBlockKey::Cards)['items'][0]['icon'])
        ->toBe('heroicon-o-lifebuoy');

    $card = $this->getJson(route('api.v1.blocks.show', ['page' => 'contacts']))
        ->assertOk()
        ->json('data.blocks.cards.items.0');

    expect($card['icon_svg'])->toStartWith('<svg');
});

/**
 * The seeded cards carry no status key — "not explicitly false" counts as
 * published — so the toggle has to hydrate as on, or the first save through
 * the form quietly unpublishes every card.
 */
it('keeps a card published across a save that never touched its switch', function (): void {
    $this->seed(ContactsSeeder::class);

    $this->actingAs($this->admin);

    $component = Livewire::test(ManageContacts::class);

    foreach ($component->get('data')['cards']['items'] as $item) {
        expect($item['status'])->toBeTrue();
    }

    $component->callAction(TestAction::make('save_cards')->schemaComponent(true));

    foreach (ContentBlock::read(PageKey::Contacts, ContentBlockKey::Cards)['items'] as $item) {
        expect($item['status'])->toBeTrue();
    }
});

it('offers every card icon rendered from the panel set', function (): void {
    foreach (ContactCardIcon::getIconOptions() as $option) {
        expect($option)->toContain('<svg');
    }
});

it('writes a card back through its save action', function (): void {
    $this->seed(ContactsSeeder::class);

    $this->actingAs($this->admin);

    $component = Livewire::test(ManageContacts::class);

    // the repeater keys its rows by uuid, so the first one has to be looked up
    $first = array_key_first($component->get('data')['cards']['items']);

    $component
        ->set("data.cards.items.{$first}.title", ['ru' => 'Новый офис', 'uz' => 'Yangi ofis'])
        ->callAction(TestAction::make('save_cards')->schemaComponent(true));

    expect(ContentBlock::read(PageKey::Contacts, ContentBlockKey::Cards)['items'][0]['title']['ru'])
        ->toBe('Новый офис');
});
