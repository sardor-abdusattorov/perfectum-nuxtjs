<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Its own file: create_faqs_table had already run on the live site, and Laravel
 * never opens a migration it has recorded, so a column added there would never
 * reach production.
 *
 * Everything starts as «both», the way it behaved before the column existed.
 * The one exception is a question that stands on the CDMA page and nowhere
 * else — that one belongs to CDMA alone, and the site already treats it so.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('faqs', 'network')) {
            return;
        }

        Schema::table('faqs', function (Blueprint $table): void {
            $table->string('network', 10)->default('both')->after('category_id')->index();
        });

        DB::table('faqs')
            ->whereJsonContains('pages', 'cdma')
            ->whereJsonLength('pages', 1)
            ->update(['network' => 'cdma']);
    }

    public function down(): void
    {
        if (! Schema::hasColumn('faqs', 'network')) {
            return;
        }

        Schema::table('faqs', function (Blueprint $table): void {
            $table->dropColumn('network');
        });
    }
};
