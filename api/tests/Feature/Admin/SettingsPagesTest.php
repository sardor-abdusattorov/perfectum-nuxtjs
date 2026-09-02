<?php

declare(strict_types=1);

use App\Filament\Pages\ProfileSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();
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
