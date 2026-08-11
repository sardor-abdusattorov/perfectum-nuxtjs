<?php

declare(strict_types=1);

use App\Models\Social;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Cache::flush();
});

it('returns published networks in the configured order', function (): void {
    Social::create(['name' => 'Instagram', 'icon' => 'si-instagram', 'url' => 'https://instagram.com/x', 'sort' => 2]);
    Social::create(['name' => 'Telegram', 'icon' => 'si-telegram', 'url' => 'https://t.me/x', 'sort' => 1]);

    $this->getJson(route('api.v1.socials'))
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.name', 'Telegram')
        ->assertJsonPath('data.1.name', 'Instagram');
});

it('hides an unpublished network', function (): void {
    Social::create(['name' => 'Telegram', 'icon' => 'si-telegram', 'url' => 'https://t.me/x', 'status' => false]);

    $this->getJson(route('api.v1.socials'))
        ->assertOk()
        ->assertExactJson(['data' => []]);
});

it('translates the blade icon name into an iconify name', function (): void {
    Social::create(['name' => 'Telegram', 'icon' => 'si-telegram', 'url' => 'https://t.me/x']);

    $this->getJson(route('api.v1.socials'))
        ->assertOk()
        ->assertJsonPath('data.0.icon', 'simple-icons:telegram');
});

it('refreshes the cache when a network changes', function (): void {
    $social = Social::create(['name' => 'Telegram', 'icon' => 'si-telegram', 'url' => 'https://t.me/x']);

    $this->getJson(route('api.v1.socials'))->assertJsonPath('data.0.url', 'https://t.me/x');

    $social->update(['url' => 'https://t.me/perfectum']);

    $this->getJson(route('api.v1.socials'))->assertJsonPath('data.0.url', 'https://t.me/perfectum');
});
