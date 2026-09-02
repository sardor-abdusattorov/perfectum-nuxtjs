<?php

declare(strict_types=1);

use App\Filament\Pages\ProfileSettings;
use App\Filament\Pages\Settings;
use App\Models\Settings as SettingsModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();
});

it('stores the maps key from the settings page where the site can read it', function (): void {
    SettingsModel::set('seo.title', ['ru' => 'Perfectum', 'uz' => 'Perfectum']);
    SettingsModel::set('seo.description', ['ru' => 'Оператор', 'uz' => 'Operator']);

    $this->actingAs(panelUser(['View:Settings']));

    Livewire::test(Settings::class)
        ->fillForm(['maps.yandex_key' => 'abc-123'])
        ->call('save')
        ->assertHasNoFormErrors();

    SettingsModel::forgetValues();

    expect(SettingsModel::get('maps.yandex_key'))->toBe('abc-123');

    $this->getJson(route('api.v1.site'))
        ->assertJsonPath('data.settings.maps.yandex_key', 'abc-123');
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
