<?php

declare(strict_types=1);

use App\Filament\Resources\ServiceCategories\Pages\CreateServiceCategory;
use App\Models\ServiceCategory;
use App\Support\Slug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();
});

it('boils a pasted address down to the slug it carries', function (string $pasted, string $expected): void {
    expect(Slug::fromInput($pasted))->toBe($expected);
})->with([
    'listing filtered by the category' => ['/services?category=uslugi-mobilnoy-svyazi-5g-sa', 'uslugi-mobilnoy-svyazi-5g-sa'],
    'the whole address from the browser' => ['https://perfectum.uz/ru/services?category=uslugi-5g', 'uslugi-5g'],
    'a record page' => ['/news/otkrytie-ofisa', 'otkrytie-ofisa'],
    'a path with a trailing slash' => ['/pages/o-kompanii/', 'o-kompanii'],
    'an address with an anchor' => ['/cdma#cdma-news', 'cdma'],
    'a plain slug is left alone' => ['uslugi-5g', 'uslugi-5g'],
    'russian text is transliterated' => ['Услуги 5G', 'uslugi-5g'],
]);

it('leaves an empty value empty so the title still fills it in', function (?string $value): void {
    expect(Slug::fromInput($value))->toBe('');
})->with([[null], [''], ['   '], ['/'], ['///']]);

/**
 * Pasting the address of the listing is what the team actually did, and the
 * form answered with «must only contain letters, numbers, dashes and
 * underscores» instead of taking the slug out of it.
 */
it('accepts the address pasted into the slug field', function (): void {
    $this->actingAs(panelUser(['ViewAny:ServiceCategory', 'Create:ServiceCategory']));

    Livewire::test(CreateServiceCategory::class)
        ->fillForm([
            'name.ru' => 'Услуги мобильной связи 5G SA',
            'name.uz' => 'Mobil aloqa xizmatlari 5G SA',
            'slug' => '/services?category=uslugi-mobilnoy-svyazi-5g-sa',
            'network' => '5g',
            'sort' => 1,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(ServiceCategory::query()->value('slug'))->toBe('uslugi-mobilnoy-svyazi-5g-sa');
});
