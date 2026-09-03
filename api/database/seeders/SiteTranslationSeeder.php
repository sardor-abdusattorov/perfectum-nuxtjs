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
            'error.not_found_title' => ['ru' => 'Такой страницы нет', 'uz' => 'Bunday sahifa yo‘q'],
            'error.not_found_text' => [
                'ru' => 'Возможно, адрес набран с опечаткой или страница переехала. Загляните в разделы ниже — то, что вы искали, скорее всего там.',
                'uz' => 'Manzil xato terilgan yoki sahifa ko‘chirilgan bo‘lishi mumkin. Quyidagi bo‘limlarga qarang — izlaganingiz katta ehtimol o‘sha yerda.',
            ],
            'error.server_title' => ['ru' => 'Что-то пошло не так', 'uz' => 'Nimadir noto‘g‘ri ketdi'],
            'error.server_text' => [
                'ru' => 'Мы уже знаем о неполадке и чиним её. Попробуйте обновить страницу через минуту.',
                'uz' => 'Nosozlikdan xabardormiz va tuzatyapmiz. Bir daqiqadan so‘ng sahifani yangilab ko‘ring.',
            ],
            'error.home' => ['ru' => 'На главную', 'uz' => 'Bosh sahifaga'],
            'error.links_label' => ['ru' => 'Разделы сайта', 'uz' => 'Sayt bo‘limlari'],
            'error.link_tariffs' => ['ru' => 'Тарифы', 'uz' => 'Tariflar'],
            'error.link_services' => ['ru' => 'Услуги', 'uz' => 'Xizmatlar'],
            'error.link_devices' => ['ru' => 'Устройства', 'uz' => 'Qurilmalar'],
            'error.link_coverage' => ['ru' => 'Карта покрытия', 'uz' => 'Qamrov xaritasi'],
            'error.link_news' => ['ru' => 'Новости', 'uz' => 'Yangiliklar'],
            'error.link_contact' => ['ru' => 'Связаться с нами', 'uz' => 'Biz bilan bog‘lanish'],
        ];
    }
}
