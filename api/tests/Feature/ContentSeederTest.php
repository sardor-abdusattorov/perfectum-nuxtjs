<?php

declare(strict_types=1);

use App\Enums\Network;
use App\Models\News;
use Database\Seeders\ContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('files the maintenance notices under 5G and the rest of the archive under CDMA', function (): void {
    $this->seed(ContentSeeder::class);

    $networks = News::query()
        ->get()
        ->groupBy(fn (News $news): string => preg_match('/техническ|профилактич/iu', $news->getTranslation('title', 'ru')) === 1
            ? 'maintenance'
            : 'archive')
        ->map(fn ($group): array => $group->pluck('network')->unique()->map->value->all());

    expect($networks['maintenance'])->toBe([Network::FiveG->value])
        ->and($networks['archive'])->toBe([Network::Cdma->value]);
});
