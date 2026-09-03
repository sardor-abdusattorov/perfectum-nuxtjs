<?php

declare(strict_types=1);

use App\Models\Page;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

/**
 * Puts the pages table back the way the running site has it: no parent, no
 * sort, and content the database refuses to leave empty.
 */
function rollPagesBackToTheFlatShape(): void
{
    Schema::dropIfExists('pages');

    Schema::create('pages', function (Blueprint $table): void {
        $table->id();
        $table->string('slug')->unique();
        $table->json('title');
        $table->json('content');
        $table->string('image')->nullable();
        $table->json('meta_title')->nullable();
        $table->json('meta_description')->nullable();
        $table->json('redirect_from')->nullable();
        $table->boolean('status')->default(true)->index();
        $table->unsignedBigInteger('views')->default(0);
        $table->timestamps();
    });
}

function runThePageCardsMigration(): void
{
    $migration = require database_path('migrations/2026_09_03_140000_add_card_columns_to_pages.php');

    $migration->up();
}

/**
 * The site is live with pages already written, so the columns cannot arrive by
 * rebuilding the table — the panel queries `parent_id` on every listing and
 * throws a 500 without it.
 */
it('gives a live pages table the columns the card hub needs', function (): void {
    rollPagesBackToTheFlatShape();

    DB::table('pages')->insert([
        'slug' => 'oferta',
        'title' => json_encode(['ru' => 'Оферта']),
        'content' => json_encode(['ru' => '<p>Текст</p>']),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    runThePageCardsMigration();

    expect(Schema::hasColumn('pages', 'parent_id'))->toBeTrue()
        ->and(Schema::hasColumn('pages', 'sort'))->toBeTrue();

    $page = Page::query()->where('slug', 'oferta')->first();

    expect($page->parent_id)->toBeNull()
        ->and($page->sort)->toBe(0)
        ->and($page->title)->toBe('Оферта');
});

it('lets a hub page be saved without any text of its own', function (): void {
    rollPagesBackToTheFlatShape();
    runThePageCardsMigration();

    $hub = Page::create([
        'slug' => 'polezno-znat',
        'title' => ['ru' => 'Полезно знать'],
        'status' => true,
    ]);

    expect($hub->fresh()->content)->toBeEmpty();
});

it('runs twice without complaining, as a re-run deploy would', function (): void {
    rollPagesBackToTheFlatShape();

    runThePageCardsMigration();
    runThePageCardsMigration();

    expect(Schema::hasColumn('pages', 'parent_id'))->toBeTrue();
});
