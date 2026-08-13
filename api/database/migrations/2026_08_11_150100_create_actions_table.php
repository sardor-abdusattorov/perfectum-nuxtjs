<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('action_categories')->nullOnDelete();
            $table->string('network', 10)->default('5g')->index();
            $table->json('title');
            $table->string('slug')->unique();
            $table->json('badge')->nullable();
            $table->json('excerpt')->nullable();
            $table->json('content');
            $table->string('preview_image')->nullable();
            $table->string('main_image')->nullable();
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['status', 'ends_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actions');
    }
};
