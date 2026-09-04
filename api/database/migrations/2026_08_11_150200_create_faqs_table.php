<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('faq_categories')->nullOnDelete();
            $table->string('network', 10)->default('both')->index();
            $table->json('question');
            $table->json('answer');
            $table->json('pages');
            $table->integer('sort')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['status', 'sort']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
