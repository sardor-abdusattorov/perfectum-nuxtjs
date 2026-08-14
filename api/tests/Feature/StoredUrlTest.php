<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    Storage::fake('public');
});

it('builds the public url for a file that is there', function (): void {
    Storage::disk('public')->put('images/logo.svg', 'svg');

    expect(stored_url('images/logo.svg'))->toEndWith('/storage/images/logo.svg');
});

it('answers nothing for a path whose file was never uploaded', function (): void {
    expect(stored_url('images/logo.svg'))->toBeNull();
});

it('answers nothing for an empty or non-string value', function (mixed $value): void {
    expect(stored_url($value))->toBeNull();
})->with([[null], [''], ['   '], [[]], [42]]);

it('hands back an absolute url untouched', function (): void {
    expect(stored_url('https://cdn.example.com/logo.svg'))->toBe('https://cdn.example.com/logo.svg');
});
