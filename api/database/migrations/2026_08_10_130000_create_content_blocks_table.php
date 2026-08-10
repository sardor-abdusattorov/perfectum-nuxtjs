<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('page')->index();
            $table->string('key');
            $table->json('data');
            $table->timestamps();

            $table->unique(['page', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_blocks');
    }
};
