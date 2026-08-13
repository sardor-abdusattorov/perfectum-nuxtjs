<?php

declare(strict_types=1);

use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function makeNews(bool $featured = false): News
{
    return News::create([
        'title' => ['ru' => 'Новость '.fake()->unique()->numerify('###')],
        'slug' => 'news-'.fake()->unique()->numerify('###'),
        'content' => ['ru' => '<p>Текст</p>'],
        'is_featured' => $featured,
        'published_at' => now(),
    ]);
}

it('keeps a single featured news item', function (): void {
    $first = makeNews(true);
    $second = makeNews(true);

    expect($first->fresh()->is_featured)->toBeFalse();
    expect($second->fresh()->is_featured)->toBeTrue();
    expect(News::where('is_featured', true)->count())->toBe(1);
});

it('releases the previous one when an existing item is promoted', function (): void {
    $held = makeNews(true);
    $other = makeNews();

    $other->update(['is_featured' => true]);

    expect($held->fresh()->is_featured)->toBeFalse();
    expect($other->fresh()->is_featured)->toBeTrue();
});

it('leaves the others alone when a plain item is saved', function (): void {
    $held = makeNews(true);
    $other = makeNews();

    $other->update(['title' => ['ru' => 'Правка']]);

    expect($held->fresh()->is_featured)->toBeTrue();
    expect(News::where('is_featured', true)->count())->toBe(1);
});
