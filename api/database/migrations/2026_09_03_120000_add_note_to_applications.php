<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A fresh database gets the column from create_applications_table; this carries
 * the running site across.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('applications', 'note')) {
            return;
        }

        Schema::table('applications', function (Blueprint $table): void {
            $table->text('note')->nullable()->after('processed_at');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('applications', 'note')) {
            return;
        }

        Schema::table('applications', function (Blueprint $table): void {
            $table->dropColumn('note');
        });
    }
};
