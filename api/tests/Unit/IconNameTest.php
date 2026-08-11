<?php

declare(strict_types=1);

use App\Support\IconName;

it('maps a simple icons name', function (): void {
    expect(IconName::toIconify('si-telegram'))->toBe('simple-icons:telegram');
});

it('maps every heroicon variant', function (string $blade, string $iconify): void {
    expect(IconName::toIconify($blade))->toBe($iconify);
})->with([
    ['heroicon-o-globe-alt', 'heroicons:globe-alt'],
    ['heroicon-s-globe-alt', 'heroicons:globe-alt-solid'],
    ['heroicon-m-globe-alt', 'heroicons:globe-alt-16-solid'],
]);

it('leaves an iconify name untouched', function (): void {
    expect(IconName::toIconify('simple-icons:telegram'))->toBe('simple-icons:telegram');
});

it('returns null for an empty value', function (): void {
    expect(IconName::toIconify(null))->toBeNull()
        ->and(IconName::toIconify(''))->toBeNull();
});

it('passes through a name from an unknown set', function (): void {
    expect(IconName::toIconify('custom-logo'))->toBe('custom-logo');
});
