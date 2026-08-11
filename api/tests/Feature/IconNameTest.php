<?php

declare(strict_types=1);

use App\Support\IconName;

it('renders an icon from an installed set', function (): void {
    $svg = IconName::svg('si-telegram');

    expect($svg)->toContain('<svg')
        ->and($svg)->toContain('viewBox')
        ->and($svg)->toContain('aria-hidden="true"');
});

it('renders an icon from the local brand set', function (): void {
    expect(IconName::svg('brand-linkedin'))->toContain('<svg');
});

it('accepts an iconify name and resolves it to an installed set', function (): void {
    expect(IconName::blade('simple-icons:facebook'))->toBe('si-facebook')
        ->and(IconName::blade('heroicons:home'))->toBe('heroicon-o-home');
});

it('falls through to the local brand set when a set dropped the icon', function (): void {
    expect(IconName::blade('si-linkedin'))->toBeNull()
        ->and(IconName::blade('simple-icons:linkedin'))->toBe('brand-linkedin');
});

it('returns null for a name no set provides', function (): void {
    expect(IconName::svg('si-nothing-like-this'))->toBeNull();
});

it('returns null for an empty name', function (): void {
    expect(IconName::svg(null))->toBeNull()
        ->and(IconName::svg(''))->toBeNull();
});

it('reports whether an icon can be rendered', function (): void {
    expect(IconName::exists('si-facebook'))->toBeTrue()
        ->and(IconName::exists('simple-icons:linkedin'))->toBeTrue()
        ->and(IconName::exists('si-linkedin'))->toBeFalse()
        ->and(IconName::exists(null))->toBeFalse();
});
