<?php

declare(strict_types=1);

use App\Models\ApplicationStatus;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

/**
 * Puts the applications table back the way the running site has it: a status
 * string, no status_id and no processed_at.
 */
function rollApplicationsBackToTheStatusString(): void
{
    Schema::dropIfExists('applications');

    Schema::create('applications', function (Blueprint $table): void {
        $table->id();
        $table->string('name')->nullable();
        $table->string('phone');
        $table->string('email')->nullable();
        $table->foreignId('theme_id')->nullable()->constrained('application_themes')->restrictOnDelete();
        $table->text('message')->nullable();
        $table->string('status', 20)->default('new')->index();
        $table->string('ip_address', 45)->nullable();
        $table->timestamps();

        $table->index('created_at');
    });
}

function runTheStatusMigration(): void
{
    $migration = require database_path('migrations/2026_09_03_100000_move_application_statuses_into_their_own_table.php');

    $migration->up();
}

/**
 * The site is live with thousands of applications, so this migration is the one
 * piece of the change that cannot be re-run from scratch — what it does to the
 * existing rows is the whole point.
 */
it('carries the old status string over to the new table', function (): void {
    rollApplicationsBackToTheStatusString();

    DB::table('applications')->insert([
        ['phone' => '+998 (90) 111-11-11', 'status' => 'new', 'created_at' => '2026-09-01 06:00:00', 'updated_at' => '2026-09-01 06:00:00'],
        ['phone' => '+998 (90) 222-22-22', 'status' => 'processed', 'created_at' => '2026-09-01 07:00:00', 'updated_at' => '2026-09-01 07:40:00'],
        ['phone' => '+998 (90) 333-33-33', 'status' => 'processed', 'created_at' => '2026-09-02 08:00:00', 'updated_at' => '2026-09-02 08:15:00'],
    ]);

    runTheStatusMigration();

    expect(Schema::hasColumn('applications', 'status'))->toBeFalse()
        ->and(Schema::hasColumn('applications', 'status_id'))->toBeTrue()
        ->and(Schema::hasColumn('applications', 'processed_at'))->toBeTrue();

    $new = ApplicationStatus::query()->where('slug', 'new')->value('id');
    $processed = ApplicationStatus::query()->where('slug', 'processed')->value('id');

    $rows = DB::table('applications')->orderBy('id')->get();

    expect($rows[0]->status_id)->toBe($new)
        ->and($rows[0]->processed_at)->toBeNull()
        ->and($rows[1]->status_id)->toBe($processed)
        ->and($rows[2]->status_id)->toBe($processed);
});

/**
 * The moment an operator handled an old application was never recorded; the
 * last time the row was touched is the closest thing the database holds.
 */
it('reads the handling time of an already processed application off its last edit', function (): void {
    rollApplicationsBackToTheStatusString();

    DB::table('applications')->insert([
        'phone' => '+998 (90) 222-22-22',
        'status' => 'processed',
        'created_at' => '2026-09-01 07:00:00',
        'updated_at' => '2026-09-01 07:40:00',
    ]);

    runTheStatusMigration();

    expect(DB::table('applications')->value('processed_at'))->toContain('2026-09-01 07:40:00');
});

it('does nothing on a database that is already in the new shape', function (): void {
    runTheStatusMigration();

    expect(Schema::hasColumn('applications', 'status'))->toBeFalse()
        ->and(Schema::hasColumn('applications', 'status_id'))->toBeTrue();
});
