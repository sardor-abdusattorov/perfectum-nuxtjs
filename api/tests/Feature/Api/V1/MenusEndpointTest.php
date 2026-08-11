<?php

declare(strict_types=1);

use App\Enums\MenuLocation;
use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Cache::flush();
});

it('groups menus by location and nests children', function (): void {
    $parent = Menu::create([
        'location' => MenuLocation::Header,
        'name' => ['ru' => 'Мобильная связь', 'uz' => 'Mobil aloqa'],
        'url' => ['ru' => '/tariffs'],
        'sort' => 1,
        'status' => true,
    ]);

    Menu::create([
        'parent_id' => $parent->id,
        'location' => MenuLocation::Header,
        'name' => ['ru' => 'Тарифы', 'uz' => 'Tariflar'],
        'url' => ['ru' => '/tariffs'],
        'sort' => 1,
        'status' => true,
    ]);

    $this->getJson(route('api.v1.menus'), ['X-Locale' => 'uz'])
        ->assertOk()
        ->assertJsonPath('data.header.0.name', 'Mobil aloqa')
        ->assertJsonPath('data.header.0.children.0.name', 'Tariflar')
        ->assertJsonPath('data.footer', []);
});

it('hides unpublished items', function (): void {
    Menu::create([
        'location' => MenuLocation::Footer,
        'name' => ['ru' => 'Черновик'],
        'sort' => 1,
        'status' => false,
    ]);

    $this->getJson(route('api.v1.menus'))
        ->assertOk()
        ->assertJsonPath('data.footer', []);
});

it('refreshes the cached tree when an item changes', function (): void {
    $item = Menu::create([
        'location' => MenuLocation::Header,
        'name' => ['ru' => 'Было'],
        'sort' => 1,
        'status' => true,
    ]);

    $this->getJson(route('api.v1.menus'))->assertJsonPath('data.header.0.name', 'Было');

    $item->update(['name' => ['ru' => 'Стало']]);

    $this->getJson(route('api.v1.menus'))->assertJsonPath('data.header.0.name', 'Стало');
});
