<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\SiteTranslation;
use Illuminate\Database\Seeder;

class SiteTranslationSeeder extends Seeder
{
    private const CATEGORY = 'app';

    public function run(): void
    {
        foreach ($this->lines() as $key => $value) {
            SiteTranslation::updateOrCreate(
                ['category' => self::CATEGORY, 'key' => $key],
                ['value' => $value, 'is_published' => true]
            );
        }
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function lines(): array
    {
        return [
            'services.files' => ['ru' => 'Файлы', 'uz' => 'Fayllar'],
            'preview.notice' => ['ru' => 'Режим просмотра', 'uz' => 'Koʻrish rejimi'],
            'preview.hint' => ['ru' => 'Черновик виден только вам по ссылке из админки', 'uz' => 'Qoralama faqat sizga, admin paneldagi havola orqali koʻrinadi'],
            'preview.exit' => ['ru' => 'Выйти', 'uz' => 'Chiqish'],
            'error.not_found_title' => ['ru' => 'Такой страницы нет', 'uz' => 'Bunday sahifa yo‘q'],
            'error.not_found_text' => [
                'ru' => 'Возможно, адрес набран с опечаткой или страница переехала.',
                'uz' => 'Manzil xato terilgan yoki sahifa ko‘chirilgan bo‘lishi mumkin.',
            ],
            'error.server_title' => ['ru' => 'Что-то пошло не так', 'uz' => 'Nimadir noto‘g‘ri ketdi'],
            'error.server_text' => [
                'ru' => 'Мы уже знаем о неполадке и чиним её. Попробуйте обновить страницу через минуту.',
                'uz' => 'Nosozlikdan xabardormiz va tuzatyapmiz. Bir daqiqadan so‘ng sahifani yangilab ko‘ring.',
            ],
            'error.home' => ['ru' => 'На главную', 'uz' => 'Bosh sahifaga'],
        ];
    }
}
