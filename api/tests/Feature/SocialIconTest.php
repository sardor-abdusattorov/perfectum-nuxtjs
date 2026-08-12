<?php

declare(strict_types=1);

use App\Models\Social;

it('renders an icon from an installed set', function (): void {
    $svg = Social::iconSvg('si-telegram');

    expect($svg)->toContain('<svg')
        ->and($svg)->toContain('viewBox')
        ->and($svg)->toContain('aria-hidden="true"');
});

it('renders an icon from the local brand set', function (): void {
    expect(Social::iconSvg('brand-linkedin'))->toContain('<svg');
});

it('accepts an iconify name and resolves it to an installed set', function (): void {
    expect(Social::iconName('simple-icons:facebook'))->toBe('si-facebook')
        ->and(Social::iconName('heroicons:home'))->toBe('heroicon-o-home');
});

it('falls through to the local brand set when a set dropped the icon', function (): void {
    expect(Social::iconName('si-linkedin'))->toBeNull()
        ->and(Social::iconName('simple-icons:linkedin'))->toBe('brand-linkedin');
});

it('returns null for a name no set provides', function (): void {
    expect(Social::iconSvg('si-nothing-like-this'))->toBeNull();
});

it('returns null for an empty name', function (): void {
    expect(Social::iconSvg(null))->toBeNull()
        ->and(Social::iconSvg(''))->toBeNull();
});

it('reports whether an icon can be rendered', function (): void {
    expect(Social::hasIcon('si-facebook'))->toBeTrue()
        ->and(Social::hasIcon('simple-icons:linkedin'))->toBeTrue()
        ->and(Social::hasIcon('si-linkedin'))->toBeFalse()
        ->and(Social::hasIcon(null))->toBeFalse();
});
