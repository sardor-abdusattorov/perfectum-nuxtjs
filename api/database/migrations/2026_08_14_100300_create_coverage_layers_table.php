<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coverage_layers', function (Blueprint $table) {
            $table->id();
            $table->string('key', 20)->unique();
            $table->json('name');
            $table->string('color', 20)->default('#e60000');
            $table->string('file')->nullable();
            $table->json('geojson')->nullable();
            $table->unsignedInteger('features')->default(0);
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('status')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coverage_layers');
    }
};
