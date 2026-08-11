<?php

declare(strict_types=1);

use App\Models\SiteTranslation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Cache::flush();
});

it('groups published translations by category', function (): void {
    SiteTranslation::create([
        'category' => 'cookie',
        'key' => 'accept',
        'value' => ['ru' => 'Принять', 'uz' => 'Qabul qilish'],
        'is_published' => true,
    ]);

    $this->getJson(route('api.v1.translations'), ['X-Locale' => 'uz'])
        ->assertOk()
        ->assertJsonPath('data.cookie.accept', 'Qabul qilish');
});

it('hides unpublished translations', function (): void {
    SiteTranslation::create([
        'category' => 'cookie',
        'key' => 'accept',
        'value' => ['ru' => 'Принять'],
        'is_published' => false,
    ]);

    $this->getJson(route('api.v1.translations'))
        ->assertOk()
        ->assertExactJson(['data' => []]);
});

it('refreshes the cache when a translation changes', function (): void {
    $translation = SiteTranslation::create([
        'category' => 'cookie',
        'key' => 'accept',
        'value' => ['ru' => 'Было'],
        'is_published' => true,
    ]);

    $this->getJson(route('api.v1.translations'))->assertJsonPath('data.cookie.accept', 'Было');

    $translation->update(['value' => ['ru' => 'Стало']]);

    $this->getJson(route('api.v1.translations'))->assertJsonPath('data.cookie.accept', 'Стало');
});
