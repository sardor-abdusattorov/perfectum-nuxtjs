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

/**
 * Заголовок и описание уходили в ключи seo.title.ru и seo.title.uz, за
 * которыми никто не приходит, а сайт всё это время показывал заводское имя из
 * APP_NAME. В админке при этом текст выглядел сохранённым.
 */
it('puts the seo tab where the site actually reads it', function (): void {
    $this->actingAs(panelUser(['View:Settings']));

    Livewire::test(Settings::class)
        ->fillForm([
            'seo.title' => ['ru' => 'Перфектум 5G', 'uz' => 'Perfectum 5G'],
            'seo.description' => ['ru' => 'Оператор связи', 'uz' => 'Aloqa operatori'],
            'seo.keywords' => ['ru' => '5G, интернет', 'uz' => '5G, internet'],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    SettingsModel::forgetValues();

    expect(SettingsModel::get('seo.title'))->toBe(['ru' => 'Перфектум 5G', 'uz' => 'Perfectum 5G']);

    $this->getJson(route('api.v1.site'))
        ->assertOk()
        ->assertJsonPath('data.settings.seo.title', 'Перфектум 5G')
        ->assertJsonPath('data.settings.seo.description', 'Оператор связи');

    $this->getJson(route('api.v1.site'), ['X-Locale' => 'uz'])
        ->assertOk()
        ->assertJsonPath('data.settings.seo.title', 'Perfectum 5G');
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
