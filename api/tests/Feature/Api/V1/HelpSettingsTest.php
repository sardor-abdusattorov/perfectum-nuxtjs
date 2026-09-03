<?php

declare(strict_types=1);

use App\Filament\Pages\Settings as SettingsPage;
use App\Models\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Cache::flush();
});

it('shows every help link until someone turns one off', function (): void {
    $this->getJson(route('api.v1.site'))
        ->assertOk()
        ->assertJsonPath('data.settings.help.faq', true)
        ->assertJsonPath('data.settings.help.numbers', true)
        ->assertJsonPath('data.settings.help.contact', true);
});

it('hides the link the admin switched off', function (): void {
    Settings::set('help.numbers', false);

    $this->getJson(route('api.v1.site'))
        ->assertOk()
        ->assertJsonPath('data.settings.help.numbers', false)
        ->assertJsonPath('data.settings.help.faq', true);
});

it('carries the switch from the settings page through to the site', function (): void {
    $this->withoutVite();

    Settings::set('seo.title', ['ru' => 'Perfectum', 'uz' => 'Perfectum']);
    Settings::set('seo.description', ['ru' => 'Оператор', 'uz' => 'Operator']);

    $this->actingAs(panelUser(['View:Settings']));

    Livewire::test(SettingsPage::class)
        ->fillForm(['help.faq' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    Settings::forgetValues();

    $this->getJson(route('api.v1.site'))
        ->assertJsonPath('data.settings.help.faq', false)
        ->assertJsonPath('data.settings.help.numbers', true);
});
