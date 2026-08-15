<?php

declare(strict_types=1);

use App\Enums\ContactCard;
use App\Enums\ContactCardIcon;
use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Filament\Pages\ManageContacts;
use App\Models\ContentBlock;
use App\Models\User;
use Database\Seeders\ContactsSeeder;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();

    $this->admin = User::factory()->create();
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

it('keeps the picked icon and leaves an unset one to the type', function (): void {
    $this->seed(ContactsSeeder::class);

    $this->actingAs($this->admin);

    $component = Livewire::test(ManageContacts::class);

    $first = array_key_first($component->get('data')['cards']['items']);

    $component
        ->set("data.cards.items.{$first}.icon", ContactCardIcon::Globe->value)
        ->callAction(TestAction::make('save_cards')->schemaComponent(true));

    $items = ContentBlock::read(PageKey::Contacts, ContentBlockKey::Cards)['items'];

    expect($items[0]['icon'])->toBe('globe')
        ->and($items[1]['icon'] ?? null)->toBeNull();
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

it('implies each icon from its card type', function (): void {
    expect(collect(ContactCard::cases())->map(fn (ContactCard $type): string => ContactCardIcon::for($type)->value)->all())
        ->toBe(['building', 'phone', 'envelope', 'globe']);
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
