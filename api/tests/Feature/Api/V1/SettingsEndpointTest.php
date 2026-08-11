<?php

declare(strict_types=1);

use App\Models\Settings;
use App\Models\SiteSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Cache::flush();
});

it('returns seo translated into the requested locale', function (): void {
    Settings::set('seo.title', ['ru' => 'Перфектум', 'uz' => 'Perfektum', 'en' => 'Perfectum']);
    Settings::set('seo.description', ['ru' => 'Описание', 'uz' => 'Tavsif', 'en' => 'Description']);

    $this->getJson(route('api.v1.settings'), ['X-Locale' => 'uz'])
        ->assertOk()
        ->assertJsonPath('data.locale', 'uz')
        ->assertJsonPath('data.seo.title', 'Perfektum')
        ->assertJsonPath('data.seo.description', 'Tavsif');
});

it('falls back to the fallback locale when a translation is missing', function (): void {
    Settings::set('seo.title', ['ru' => 'Перфектум']);

    $this->getJson(route('api.v1.settings'), ['X-Locale' => 'en'])
        ->assertOk()
        ->assertJsonPath('data.seo.title', 'Перфектум');
});

it('marks metrics as disabled when nothing is configured', function (): void {
    $this->getJson(route('api.v1.settings'))
        ->assertOk()
        ->assertJsonPath('data.metrics.enabled', false);
});

it('marks metrics as enabled once a counter is saved', function (): void {
    Settings::set('metrics.yandex', '<script>ym()</script>');

    $this->getJson(route('api.v1.settings'))
        ->assertOk()
        ->assertJsonPath('data.metrics.enabled', true);
});

it('never leaks the counter code into the settings payload', function (): void {
    Settings::set('metrics.yandex', '<script>ym()</script>');

    $this->getJson(route('api.v1.settings'))
        ->assertOk()
        ->assertDontSee('ym()');
});

it('exposes published site settings only', function (): void {
    SiteSettings::create(['name' => 'phone', 'value' => '+998 98 127 0077', 'is_published' => true]);
    SiteSettings::create(['name' => 'draft', 'value' => 'hidden', 'is_published' => false]);

    $this->getJson(route('api.v1.settings'))
        ->assertOk()
        ->assertJsonPath('data.site.phone', '+998 98 127 0077')
        ->assertJsonMissingPath('data.site.draft');
});

it('rejects an unknown locale and serves the default one', function (): void {
    Settings::set('seo.title', ['ru' => 'Перфектум']);

    $this->getJson(route('api.v1.settings'), ['X-Locale' => 'de'])
        ->assertOk()
        ->assertJsonPath('data.locale', config('app.locale'));
});
