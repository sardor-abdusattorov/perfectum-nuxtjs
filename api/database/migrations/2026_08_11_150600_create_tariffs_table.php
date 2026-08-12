<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tariffs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('tariff_categories')->nullOnDelete();
            $table->foreignId('type_id')->nullable()->constrained('tariff_types')->nullOnDelete();
            $table->json('name');
            $table->string('slug')->unique();
            $table->string('price')->nullable();
            $table->json('price_currency')->nullable();
            $table->json('price_period')->nullable();
            $table->json('features')->nullable();
            $table->json('descriptions')->nullable();
            $table->string('image')->nullable();
            $table->string('modal_image')->nullable();
            $table->json('buttons')->nullable();
            $table->integer('sort')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['status', 'sort']);
            $table->index(['category_id', 'type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tariffs');
    }
};
