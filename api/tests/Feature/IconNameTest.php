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

it('returns null for an icon no installed set provides', function (): void {
    expect(IconName::svg('si-linkedin'))->toBeNull()
        ->and(IconName::svg('simple-icons:facebook'))->toBeNull();
});

it('returns null for an empty name', function (): void {
    expect(IconName::svg(null))->toBeNull()
        ->and(IconName::svg(''))->toBeNull();
});

it('reports whether an icon can be rendered', function (): void {
    expect(IconName::exists('si-facebook'))->toBeTrue()
        ->and(IconName::exists('si-linkedin'))->toBeFalse()
        ->and(IconName::exists(null))->toBeFalse();
});
