<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('action_categories', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('slug', 96)->unique();
            $table->string('network', 10)->default('both')->index();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('status')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('action_categories');
    }
};
