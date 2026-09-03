<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A fresh database gets the column from each create_* migration. This one
 * carries the running site across, where the rows already exist.
 */
return new class extends Migration
{
    /**
     * @var array<int, string>
     */
    private const TABLES = ['news', 'actions', 'services', 'devices', 'vacancies', 'tenders', 'pages', 'tariffs'];

    public function up(): void
    {
        foreach (self::TABLES as $table) {
            if (Schema::hasColumn($table, 'views')) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint): void {
                $blueprint->unsignedBigInteger('views')->default(0);
            });
        }
    }

    public function down(): void
    {
        foreach (self::TABLES as $table) {
            if (! Schema::hasColumn($table, 'views')) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint): void {
                $blueprint->dropColumn('views');
            });
        }
    }
};
