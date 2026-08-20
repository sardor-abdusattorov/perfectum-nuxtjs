<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->foreignId('installment_partner_id')->constrained()->cascadeOnDelete();
            $table->json('options')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();

            $table->unique(['device_id', 'installment_partner_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_installments');
    }
};
