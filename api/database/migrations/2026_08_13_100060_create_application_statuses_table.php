<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @var array<int, array{slug: string, color: string, ru: string, uz: string}>
     */
    private const BASELINE = [
        ['slug' => 'new', 'color' => 'danger', 'ru' => 'Новая', 'uz' => 'Yangi'],
        ['slug' => 'processed', 'color' => 'success', 'ru' => 'Обработана', 'uz' => 'Koʻrib chiqilgan'],
        ['slug' => 'accepted', 'color' => 'info', 'ru' => 'Заявка принята', 'uz' => 'Ariza qabul qilindi'],
        ['slug' => 'no-answer', 'color' => 'warning', 'ru' => 'Не отвечает', 'uz' => 'Javob bermayapti'],
        ['slug' => 'wrong-number', 'color' => 'gray', 'ru' => 'Не существующий номер', 'uz' => 'Mavjud boʻlmagan raqam'],
        ['slug' => 'cdma-question', 'color' => 'primary', 'ru' => 'Вопрос по CDMA', 'uz' => 'CDMA boʻyicha savol'],
    ];

    public function up(): void
    {
        Schema::create('application_statuses', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('slug')->unique();
            $table->string('color', 20)->default('gray');
            $table->boolean('is_default')->default(false)->index();
            $table->integer('sort')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['status', 'sort']);
        });

        $now = now();

        foreach (self::BASELINE as $sort => $row) {
            DB::table('application_statuses')->insert([
                'name' => json_encode(['ru' => $row['ru'], 'uz' => $row['uz']], JSON_UNESCAPED_UNICODE),
                'slug' => $row['slug'],
                'color' => $row['color'],
                'is_default' => $row['slug'] === 'new',
                'sort' => $sort + 1,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('application_statuses');
    }
};
