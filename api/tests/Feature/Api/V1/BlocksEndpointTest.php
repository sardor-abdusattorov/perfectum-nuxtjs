<?php

declare(strict_types=1);

use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Models\ContentBlock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

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

it('returns an empty block list for a page without content', function (): void {
    $this->getJson(route('api.v1.blocks.show', ['page' => 'faq']))
        ->assertOk()
        ->assertJsonPath('data.blocks', []);
});

it('rejects a page key that does not exist', function (): void {
    $this->getJson('/api/v1/blocks/not-a-page')->assertNotFound();
});
