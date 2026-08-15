<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->string('type', 10)->default('office')->index();
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->string('network', 10)->default('both')->index();
            $table->string('name')->nullable();
            $table->json('district')->nullable();
            $table->json('address')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedSmallInteger('dealers_count')->nullable();
            $table->json('content')->nullable();
            $table->decimal('lat', 11, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->integer('sort')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['status', 'sort']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offices');
    }
};
