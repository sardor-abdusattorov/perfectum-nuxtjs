<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Its own file: create_news_table had already run on the live site, and Laravel
 * never opens a migration it has recorded, so a column added there would never
 * reach production.
 *
 * Everything that exists keeps the behaviour it had — an unpublished news item
 * stays unreachable until someone asks for the link themselves.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('news', 'by_link')) {
            return;
        }

        Schema::table('news', function (Blueprint $table): void {
            $table->boolean('by_link')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('news', 'by_link')) {
            return;
        }

        Schema::table('news', function (Blueprint $table): void {
            $table->dropColumn('by_link');
        });
    }
};
