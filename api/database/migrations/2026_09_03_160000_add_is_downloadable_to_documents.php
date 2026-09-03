<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A fresh database gets the column from create_documents_table. This one
 * carries the running site across, where the documents already exist — they
 * all stay downloadable, which is how they behaved until now.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('documents', 'is_downloadable')) {
            return;
        }

        Schema::table('documents', function (Blueprint $table): void {
            $table->boolean('is_downloadable')->default(true)->after('size');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('documents', 'is_downloadable')) {
            return;
        }

        Schema::table('documents', function (Blueprint $table): void {
            $table->dropColumn('is_downloadable');
        });
    }
};
