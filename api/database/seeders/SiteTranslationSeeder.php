<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\SiteTranslation;
use Illuminate\Database\Seeder;

class SiteTranslationSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->lines() as $category => $keys) {
            foreach ($keys as $key => $value) {
                SiteTranslation::updateOrCreate(
                    ['category' => $category, 'key' => $key],
                    ['value' => $value, 'is_published' => true]
                );
            }
        }
    }

    /**
     * @return array<string, array<string, array<string, string>>>
     */
    private function lines(): array
    {
        return [
            'header' => [
                'account' => ['ru' => 'Личный кабинет', 'uz' => 'Shaxsiy kabinet', 'en' => 'My account'],
                'open_menu' => ['ru' => 'Открыть меню', 'uz' => 'Menyuni ochish', 'en' => 'Open menu'],
                'close_menu' => ['ru' => 'Закрыть', 'uz' => 'Yopish', 'en' => 'Close'],
            ],

            'footer' => [
                'office_title' => ['ru' => 'Головной офис', 'uz' => 'Bosh ofis', 'en' => 'Head office'],
                'address' => [
                    'ru' => 'Узбекистан, Ташкент, ул. Тараса Шевченко 21',
                    'uz' => 'Oʻzbekiston, Toshkent, Taras Shevchenko koʻchasi 21',
                    'en' => 'Uzbekistan, Tashkent, Taras Shevchenko street 21',
                ],
                'map_link' => ['ru' => 'Показать на карте', 'uz' => 'Xaritada koʻrsatish', 'en' => 'Show on the map'],
                'phones_title' => ['ru' => 'Телефоны поддержки', 'uz' => 'Qoʻllab-quvvatlash telefonlari', 'en' => 'Support phones'],
                'phone_short_note' => [
                    'ru' => 'Для абонентов Perfectum',
                    'uz' => 'Perfectum abonentlari uchun',
                    'en' => 'For Perfectum subscribers',
                ],
                'telegram_title' => ['ru' => 'Чат с оператором в Telegram', 'uz' => 'Telegramda operator bilan chat', 'en' => 'Chat with an operator on Telegram'],
                'email_title' => ['ru' => 'Email', 'uz' => 'Email', 'en' => 'Email'],
                'email_info_note' => ['ru' => 'Для получения информации', 'uz' => 'Maʼlumot olish uchun', 'en' => 'For information'],
                'email_hotline_note' => [
                    'ru' => 'Горячая линия для жалоб и обращений',
                    'uz' => 'Shikoyat va murojaatlar uchun ishonch telefoni',
                    'en' => 'Hotline for complaints and requests',
                ],
                'copyright' => [
                    'ru' => 'ООО «RWC» (Торговая марка Perfectum) | Все права защищены | Услуги лицензированы | Цены указаны с учетом всех налогов.',
                    'uz' => '«RWC» MChJ (Perfectum savdo belgisi) | Barcha huquqlar himoyalangan | Xizmatlar litsenziyalangan | Narxlar barcha soliqlar bilan koʻrsatilgan.',
                    'en' => 'RWC LLC (Perfectum trademark) | All rights reserved | Services are licensed | Prices include all taxes.',
                ],
                'app_title' => ['ru' => 'Мобильное приложение', 'uz' => 'Mobil ilova', 'en' => 'Mobile app'],
                'socials_title' => ['ru' => 'Социальные сети', 'uz' => 'Ijtimoiy tarmoqlar', 'en' => 'Social networks'],
            ],

            'common' => [
                'back_home' => ['ru' => 'На главную', 'uz' => 'Bosh sahifaga', 'en' => 'To the home page'],
                'read_more' => ['ru' => 'Подробнее', 'uz' => 'Batafsil', 'en' => 'Read more'],
            ],

            'cookie' => [
                'text' => [
                    'ru' => 'Мы используем файлы cookie, чтобы сайт работал лучше. Продолжая пользоваться сайтом, вы соглашаетесь с их использованием.',
                    'uz' => 'Sayt yaxshiroq ishlashi uchun cookie fayllaridan foydalanamiz. Saytdan foydalanishda davom etib, ularga rozilik bildirasiz.',
                    'en' => 'We use cookies to make the site work better. By continuing to use the site you agree to them.',
                ],
                'accept' => ['ru' => 'Принять', 'uz' => 'Qabul qilish', 'en' => 'Accept'],
            ],
        ];
    }
}
