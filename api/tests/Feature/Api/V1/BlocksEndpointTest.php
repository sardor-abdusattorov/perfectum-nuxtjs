<?php

declare(strict_types=1);

use App\Enums\CdmaSection;
use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Models\ContentBlock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Cache::flush();
});

it('returns the blocks of a page resolved into the requested locale', function (): void {
    ContentBlock::write(PageKey::Home, ContentBlockKey::Marquee, [
        'items' => [
            ['text' => ['ru' => 'Безлимит', 'uz' => 'Limitsiz'], 'status' => true],
        ],
    ]);

    $this->getJson(route('api.v1.blocks.show', ['page' => 'home']), ['X-Locale' => 'uz'])
        ->assertOk()
        ->assertJsonPath('data.page', 'home')
        ->assertJsonPath('data.blocks.marquee.items.0.text', 'Limitsiz');
});

it('hands over the address of an upload, not the path it sits at', function (): void {
    Storage::fake('public');
    Storage::disk('public')->put('uploads/content-blocks/2026/08/logo.png', 'png');

    ContentBlock::write(PageKey::Home, ContentBlockKey::Marquee, [
        'items' => [
            ['image' => 'uploads/content-blocks/2026/08/logo.png', 'status' => true],
        ],
    ]);

    $image = $this->getJson(route('api.v1.blocks.show', ['page' => 'home']))
        ->assertOk()
        ->json('data.blocks.marquee.items.0.image');

    expect($image)->toBe(Storage::disk('public')->url('uploads/content-blocks/2026/08/logo.png'))
        ->and($image)->toStartWith('/storage/');
});

it('leaves out an upload the disk no longer holds', function (): void {
    Storage::fake('public');

    ContentBlock::write(PageKey::Home, ContentBlockKey::Marquee, [
        'items' => [
            ['image' => 'uploads/content-blocks/2026/08/gone.png', 'status' => true],
        ],
    ]);

    $this->getJson(route('api.v1.blocks.show', ['page' => 'home']))
        ->assertOk()
        ->assertJsonPath('data.blocks.marquee.items.0.image', null);
});

it('never lets a bare upload path reach any page of the site', function (): void {
    $this->seed();

    foreach (PageKey::cases() as $page) {
        $payload = $this->getJson(route('api.v1.blocks.show', ['page' => $page->value]))
            ->assertOk()
            ->json('data.blocks');

        expect(json_encode($payload, JSON_UNESCAPED_SLASHES))
            ->not->toContain('"uploads/', "страница {$page->value} отдаёт сырой путь загрузки");
    }
});

it('returns an empty block list for a page without content', function (): void {
    $this->getJson(route('api.v1.blocks.show', ['page' => 'faq']))
        ->assertOk()
        ->assertJsonPath('data.blocks', []);
});

it('rejects a page key that does not exist', function (): void {
    $this->getJson('/api/v1/blocks/not-a-page')->assertNotFound();
});

it('hands the cdma sections out in the stored order', function (): void {
    ContentBlock::write(PageKey::Cdma, ContentBlockKey::Sections, [
        'items' => [
            ['section' => CdmaSection::News->value, 'title' => ['ru' => 'Новости', 'uz' => 'Yangiliklar'], 'status' => true],
            ['section' => CdmaSection::Tariffs->value, 'title' => ['ru' => 'Тарифы', 'uz' => 'Tariflar'], 'status' => true],
            ['section' => CdmaSection::Dealers->value, 'title' => ['ru' => 'Дилеры', 'uz' => 'Dilerlar'], 'status' => false],
        ],
    ]);

    $items = $this->getJson(route('api.v1.blocks.show', ['page' => 'cdma']))
        ->assertOk()
        ->json('data.blocks.sections.items');

    expect(array_column($items, 'section'))->toBe(['news', 'tariffs', 'dealers'])
        ->and($items[0]['title'])->toBe('Новости')
        ->and($items[2]['status'])->toBeFalse();
});

it('names a target on the site for every section the panel offers', function (): void {
    foreach (CdmaSection::cases() as $case) {
        expect($case->target())->not->toBe('');
    }

    expect(CdmaSection::Dealers->target())->toBe('/cdma/dealers')
        ->and(CdmaSection::Tariffs->target())->toBe('#cdma-tariffs');
});
