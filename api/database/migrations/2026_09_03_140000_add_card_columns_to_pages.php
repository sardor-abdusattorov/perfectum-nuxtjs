<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A fresh database gets these from create_pages_table. This one carries the
 * running site across, where the table already exists: a page can now gather
 * other pages as cards, so it needs a parent, an order and the right to have
 * no text of its own.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table): void {
            if (! Schema::hasColumn('pages', 'parent_id')) {
                $table->foreignId('parent_id')->nullable()->after('id')->constrained('pages')->nullOnDelete();
            }

            if (! Schema::hasColumn('pages', 'sort')) {
                $table->unsignedInteger('sort')->default(0)->after('image');
            }

            if (! Schema::hasColumn('pages', 'is_group')) {
                $table->boolean('is_group')->default(false)->after('image');
            }

            $table->json('content')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table): void {
            if (Schema::hasColumn('pages', 'parent_id')) {
                $table->dropForeign(['parent_id']);
                $table->dropColumn('parent_id');
            }

            if (Schema::hasColumn('pages', 'sort')) {
                $table->dropColumn('sort');
            }

            if (Schema::hasColumn('pages', 'is_group')) {
                $table->dropColumn('is_group');
            }
        });
    }
};
