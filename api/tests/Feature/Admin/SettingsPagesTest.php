<?php

declare(strict_types=1);

use App\Enums\PageKey;
use App\Filament\Pages\ManageDocuments;
use App\Filament\Pages\ProfileSettings;
use App\Filament\Pages\Settings;
use App\Models\ContentBlock;
use App\Models\Settings as SettingsModel;
use Database\Seeders\DocumentSeeder;
use Database\Seeders\SettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();
});

function pageUser(string $page): void
{
    $user = panelUser();
    $user->givePermissionTo(Permission::findOrCreate("View:{$page}", 'web'));

    test()->actingAs($user->refresh());
}

it('saves the main settings without losing the seo values', function (): void {
    $this->seed(SettingsSeeder::class);

    pageUser('Settings');

    Livewire::test(Settings::class)
        ->call('save')
        ->assertHasNoFormErrors();

    expect(SettingsModel::get('seo.title'))->not->toBeEmpty();
});

it('renders the documents page manager and writes its block through', function (): void {
    $this->seed(DocumentSeeder::class);

    pageUser('ManageDocuments');

    foreach (ManageDocuments::tabs() as $tab) {
        expect(ContentBlock::read(PageKey::Documents, $tab::key()))
            ->not->toBeEmpty("блок {$tab::key()->value} пустой");
    }

    Livewire::test(ManageDocuments::class)->assertOk();

    $tab = ManageDocuments::tabs()[0];
    $tab::save([...ContentBlock::read(PageKey::Documents, $tab::key()), 'title' => ['ru' => 'Документы и отчёты']]);

    expect(ContentBlock::read(PageKey::Documents, $tab::key())['title']['ru'])->toBe('Документы и отчёты')
        ->and(content_blocks(PageKey::Documents)[$tab::key()->value]['title'])->toBe('Документы и отчёты');
});

it('changes the name and the password on the profile page', function (): void {
    $user = panelUser();
    $this->actingAs($user);

    Livewire::test(ProfileSettings::class)
        ->fillForm(['name' => 'Комил'], 'profileForm')
        ->call('updateProfile')
        ->assertHasNoFormErrors();

    expect($user->refresh()->name)->toBe('Комил');

    Livewire::test(ProfileSettings::class)
        ->fillForm([
            'current_password' => 'password',
            'password' => 'nOvyi-Parol-2026',
            'password_confirmation' => 'nOvyi-Parol-2026',
        ], 'passwordForm')
        ->call('updatePassword')
        ->assertHasNoFormErrors();

    expect(Hash::check('nOvyi-Parol-2026', $user->refresh()->password))->toBeTrue();
});
