<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Its own file on purpose. The column belongs with parent_id and sort, but that
 * migration had already run on the live site — Laravel keeps it in the
 * migrations table and never looks at the file again, so anything added there
 * afterwards never reaches production. A new file is the only thing that runs.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('pages', 'is_group')) {
            return;
        }

        Schema::table('pages', function (Blueprint $table): void {
            $table->boolean('is_group')->default(false)->after('image');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('pages', 'is_group')) {
            return;
        }

        Schema::table('pages', function (Blueprint $table): void {
            $table->dropColumn('is_group');
        });
    }
};
