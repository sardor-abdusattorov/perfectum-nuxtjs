<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('device_categories')->nullOnDelete();
            $table->json('name');
            $table->string('slug')->unique();
            $table->foreignId('brand_id')->nullable()->constrained('device_brands')->nullOnDelete();
            $table->json('excerpt')->nullable();
            $table->json('content')->nullable();
            $table->json('specs')->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('price')->nullable();
            $table->boolean('in_stock')->default(true);
            $table->integer('sort')->default(0);
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();

            $table->index(['status', 'sort']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
