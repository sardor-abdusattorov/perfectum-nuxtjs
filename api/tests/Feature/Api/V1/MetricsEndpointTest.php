<?php

declare(strict_types=1);

use App\Models\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Cache::flush();
});

it('returns an empty payload when no counter is configured', function (): void {
    $this->getJson(route('api.v1.metrics'))
        ->assertOk()
        ->assertExactJson(['data' => []]);
});

it('returns only the counters that are filled in', function (): void {
    Settings::set('metrics.yandex', '<script>ym()</script>');
    Settings::set('metrics.google', '');

    $this->getJson(route('api.v1.metrics'))
        ->assertOk()
        ->assertJsonPath('data.yandex', '<script>ym()</script>')
        ->assertJsonMissingPath('data.google');
});
