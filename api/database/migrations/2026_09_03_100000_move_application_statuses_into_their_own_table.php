<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * A fresh database already gets this shape from create_applications_table. This
 * one carries a live database across: the site is running with thousands of
 * applications, so the old `status` string is read into the new table before it
 * is dropped rather than rebuilt from nothing.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('applications', 'status')) {
            return;
        }

        Schema::table('applications', function (Blueprint $table): void {
            if (! Schema::hasColumn('applications', 'status_id')) {
                $table->foreignId('status_id')->nullable()->after('message')
                    ->constrained('application_statuses')->restrictOnDelete();
            }

            if (! Schema::hasColumn('applications', 'processed_at')) {
                $table->timestamp('processed_at')->nullable()->after('status_id');
            }
        });

        $ids = DB::table('application_statuses')->pluck('id', 'slug');

        foreach (['new', 'processed'] as $slug) {
            if (! isset($ids[$slug])) {
                continue;
            }

            DB::table('applications')->where('status', $slug)->update(['status_id' => $ids[$slug]]);
        }

        DB::table('applications')
            ->where('status', 'processed')
            ->whereNull('processed_at')
            ->update(['processed_at' => DB::raw('updated_at')]);

        Schema::table('applications', function (Blueprint $table): void {
            $table->dropIndex('applications_status_index');
        });

        Schema::table('applications', function (Blueprint $table): void {
            $table->dropColumn('status');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('applications', 'status')) {
            return;
        }

        Schema::table('applications', function (Blueprint $table): void {
            $table->string('status', 20)->default('new')->index()->after('message');
        });

        $slugs = DB::table('application_statuses')->pluck('slug', 'id');

        foreach ($slugs as $id => $slug) {
            DB::table('applications')->where('status_id', $id)->update([
                'status' => in_array($slug, ['new', 'processed'], true) ? $slug : 'processed',
            ]);
        }
    }
};
