<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('service_categories')->nullOnDelete();
            $table->json('name');
            $table->string('slug')->unique();
            $table->json('excerpt')->nullable();
            $table->json('lead')->nullable();
            $table->json('content')->nullable();
            $table->json('price')->nullable();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->string('ussd')->nullable();
            $table->json('facts')->nullable();
            $table->json('steps')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('sort')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['status', 'sort']);
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
