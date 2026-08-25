<?php

declare(strict_types=1);

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

/**
 * The admin stores an upload as the bare path it occupies on the disk. Every
 * other payload turns that into an address before it leaves; a block used to go
 * out untouched, so the page received `uploads/…` and the browser resolved it
 * against whatever address the visitor happened to be on.
 */
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
