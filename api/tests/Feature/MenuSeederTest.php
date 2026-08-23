<?php

declare(strict_types=1);

use App\Enums\MenuLocation;
use App\Models\Menu;
use Database\Seeders\MenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function headerParent(string $key): Menu
{
    return Menu::query()
        ->where('location', MenuLocation::Header)
        ->whereNull('parent_id')
        ->where('key', $key)
        ->sole();
}

it('leaves a gathering section without an address in any language', function (): void {
    $this->seed(MenuSeeder::class);

    foreach (['mobile', 'home'] as $key) {
        $section = headerParent($key);

        expect($section->getAttributes()['url'])->toBeNull()
            ->and($section->children()->count())->toBeGreaterThan(0);

        foreach (config('app.locales') as $locale) {
            expect($section->getTranslation('url', $locale, useFallbackLocale: false))->toBeEmpty();
        }
    }
});

/**
 * The address is translated, so writing null over it clears only the language
 * the seeder runs in — a second pass used to leave the Uzbek address behind,
 * and the header went on linking the heading through the fallback.
 */
it('clears an address the tree no longer carries, in every language', function (): void {
    $this->seed(MenuSeeder::class);

    headerParent('mobile')->update(['url' => ['ru' => '/tariffs', 'uz' => '/tariffs']]);

    $this->seed(MenuSeeder::class);

    expect(headerParent('mobile')->getAttributes()['url'])->toBeNull();
});

it('keeps the address of a section that has a page of its own', function (): void {
    $this->seed(MenuSeeder::class);

    $cdma = headerParent('cdma');

    expect($cdma->getTranslation('url', 'ru'))->toBe('/cdma')
        ->and($cdma->getTranslation('url', 'uz'))->toBe('/cdma')
        ->and($cdma->children()->count())->toBe(6);
});
