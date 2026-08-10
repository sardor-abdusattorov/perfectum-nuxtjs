<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('menus')->cascadeOnDelete();
            $table->string('location', 20);
            $table->unsignedTinyInteger('column_position')->nullable();
            $table->json('name');
            $table->json('url')->nullable();
            $table->boolean('open_in_new_tab')->default(false);
            $table->integer('sort')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['location', 'status', 'sort']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
