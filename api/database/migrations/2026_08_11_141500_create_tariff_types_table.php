<?php

use App\Support\Taxonomy;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tariff_types', function (Blueprint $table) {
            Taxonomy::columns($table);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tariff_types');
    }
};
