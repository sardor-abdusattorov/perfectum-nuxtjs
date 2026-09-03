<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A fresh database gets the column from create_services_table. This one carries
 * the running site across: a service now holds its own documents, the way a
 * tender already does.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('services', 'files')) {
            return;
        }

        Schema::table('services', function (Blueprint $table): void {
            $table->json('files')->nullable()->after('steps');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('services', 'files')) {
            return;
        }

        Schema::table('services', function (Blueprint $table): void {
            $table->dropColumn('files');
        });
    }
};
