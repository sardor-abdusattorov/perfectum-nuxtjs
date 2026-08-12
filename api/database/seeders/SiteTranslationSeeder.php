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
            'header.account' => ['ru' => 'Личный кабинет', 'uz' => 'Shaxsiy kabinet', 'en' => 'My account'],
            'header.open_menu' => ['ru' => 'Открыть меню', 'uz' => 'Menyuni ochish', 'en' => 'Open menu'],
            'header.close_menu' => ['ru' => 'Закрыть', 'uz' => 'Yopish', 'en' => 'Close'],

            'footer.office_title' => ['ru' => 'Головной офис', 'uz' => 'Bosh ofis', 'en' => 'Head office'],
            'footer.address' => [
                'ru' => 'Узбекистан, Ташкент, ул. Тараса Шевченко 21',
                'uz' => 'Oʻzbekiston, Toshkent, Taras Shevchenko koʻchasi 21',
                'en' => 'Uzbekistan, Tashkent, Taras Shevchenko street 21',
            ],
            'footer.map_link' => ['ru' => 'Показать на карте', 'uz' => 'Xaritada koʻrsatish', 'en' => 'Show on the map'],
            'footer.phones_title' => ['ru' => 'Телефоны поддержки', 'uz' => 'Qoʻllab-quvvatlash telefonlari', 'en' => 'Support phones'],
            'footer.phone_short_note' => [
                'ru' => 'Для абонентов Perfectum',
                'uz' => 'Perfectum abonentlari uchun',
                'en' => 'For Perfectum subscribers',
            ],
            'footer.telegram_title' => [
                'ru' => 'Чат с оператором в Telegram',
                'uz' => 'Telegramda operator bilan chat',
                'en' => 'Chat with an operator on Telegram',
            ],
            'footer.email_title' => ['ru' => 'Email', 'uz' => 'Email', 'en' => 'Email'],
            'footer.email_info_note' => [
                'ru' => 'Для получения информации',
                'uz' => 'Maʼlumot olish uchun',
                'en' => 'For information',
            ],
            'footer.email_hotline_note' => [
                'ru' => 'Горячая линия для жалоб и обращений',
                'uz' => 'Shikoyat va murojaatlar uchun ishonch telefoni',
                'en' => 'Hotline for complaints and requests',
            ],
            'footer.nav_label' => [
                'ru' => 'Навигация в подвале',
                'uz' => 'Pastki qismdagi navigatsiya',
                'en' => 'Footer navigation',
            ],
            'footer.copyright' => [
                'ru' => 'ООО «RWC» (Торговая марка Perfectum) | Все права защищены | Услуги лицензированы | Цены указаны с учетом всех налогов.',
                'uz' => '«RWC» MChJ (Perfectum savdo belgisi) | Barcha huquqlar himoyalangan | Xizmatlar litsenziyalangan | Narxlar barcha soliqlar bilan koʻrsatilgan.',
                'en' => 'RWC LLC (Perfectum trademark) | All rights reserved | Services are licensed | Prices include all taxes.',
            ],
            'footer.app_title' => ['ru' => 'Мобильное приложение', 'uz' => 'Mobil ilova', 'en' => 'Mobile app'],
            'footer.socials_title' => ['ru' => 'Социальные сети', 'uz' => 'Ijtimoiy tarmoqlar', 'en' => 'Social networks'],

            'common.back_home' => ['ru' => 'На главную', 'uz' => 'Bosh sahifaga', 'en' => 'To the home page'],
            'common.store_prefix' => ['ru' => 'скачать в', 'uz' => 'yuklab olish', 'en' => 'download on'],
            'common.read_more' => ['ru' => 'Подробнее', 'uz' => 'Batafsil', 'en' => 'Read more'],

            'seo.about' => ['ru' => 'О компании', 'uz' => 'Kompaniya haqida', 'en' => 'About the company'],
            'seo.actions' => ['ru' => 'Акции', 'uz' => 'Aksiyalar', 'en' => 'Special offers'],
            'seo.action' => ['ru' => 'Акция', 'uz' => 'Aksiya', 'en' => 'Special offer'],
            'seo.careers' => ['ru' => 'Карьера', 'uz' => 'Karyera', 'en' => 'Careers'],
            'seo.vacancy' => ['ru' => 'Вакансия', 'uz' => 'Vakansiya', 'en' => 'Vacancy'],
            'seo.contacts' => ['ru' => 'Контакты', 'uz' => 'Kontaktlar', 'en' => 'Contacts'],
            'seo.coverage' => ['ru' => 'Карта покрытия', 'uz' => 'Qamrov xaritasi', 'en' => 'Coverage map'],
            'seo.devices' => ['ru' => 'Каталог устройств', 'uz' => 'Qurilmalar katalogi', 'en' => 'Device catalogue'],
            'seo.device' => ['ru' => 'Устройство', 'uz' => 'Qurilma', 'en' => 'Device'],
            'seo.documents' => ['ru' => 'Документы', 'uz' => 'Hujjatlar', 'en' => 'Documents'],
            'seo.faq' => ['ru' => 'Часто задаваемые вопросы', 'uz' => 'Koʻp beriladigan savollar', 'en' => 'Frequently asked questions'],
            'seo.help' => ['ru' => 'Помощь', 'uz' => 'Yordam', 'en' => 'Help'],
            'seo.help_contact' => ['ru' => 'Связаться с нами', 'uz' => 'Biz bilan bogʻlanish', 'en' => 'Contact us'],
            'seo.news' => ['ru' => 'Новости', 'uz' => 'Yangiliklar', 'en' => 'News'],
            'seo.news_item' => ['ru' => 'Новость', 'uz' => 'Yangilik', 'en' => 'News'],
            'seo.numbers' => ['ru' => 'Свободные номера', 'uz' => 'Boʻsh raqamlar', 'en' => 'Available numbers'],
            'seo.offices' => ['ru' => 'Офисы продаж', 'uz' => 'Sotuv ofislari', 'en' => 'Sales offices'],
            'seo.procurement' => ['ru' => 'Закупки', 'uz' => 'Xaridlar', 'en' => 'Procurement'],
            'seo.tender' => ['ru' => 'Закупка', 'uz' => 'Xarid', 'en' => 'Tender'],
            'seo.services' => ['ru' => 'Услуги', 'uz' => 'Xizmatlar', 'en' => 'Services'],
            'seo.tariffs' => ['ru' => 'Тарифы', 'uz' => 'Tariflar', 'en' => 'Tariffs'],
            'seo.tariffs_archive' => ['ru' => 'Архив тарифов', 'uz' => 'Tariflar arxivi', 'en' => 'Tariff archive'],
            'seo.tariff' => ['ru' => 'Тариф', 'uz' => 'Tarif', 'en' => 'Tariff'],

            'seo.cdma' => ['ru' => 'CDMA', 'uz' => 'CDMA', 'en' => 'CDMA'],
            'seo.cdma_actions' => ['ru' => 'Акции CDMA', 'uz' => 'CDMA aksiyalari', 'en' => 'CDMA special offers'],
            'seo.cdma_connect' => ['ru' => 'Как подключиться', 'uz' => 'Qanday ulanish kerak', 'en' => 'How to connect'],
            'seo.cdma_dealers' => ['ru' => 'Дилеры CDMA', 'uz' => 'CDMA dilerlari', 'en' => 'CDMA dealers'],
            'seo.cdma_news' => ['ru' => 'Новости CDMA', 'uz' => 'CDMA yangiliklari', 'en' => 'CDMA news'],
            'seo.cdma_service' => ['ru' => 'Услуга CDMA', 'uz' => 'CDMA xizmati', 'en' => 'CDMA service'],
            'seo.cdma_tariff' => ['ru' => 'Тариф CDMA', 'uz' => 'CDMA tarifi', 'en' => 'CDMA tariff'],

            'cookie.text' => [
                'ru' => 'Мы используем файлы cookie, чтобы сайт работал лучше. Продолжая пользоваться сайтом, вы соглашаетесь с их использованием.',
                'uz' => 'Sayt yaxshiroq ishlashi uchun cookie fayllaridan foydalanamiz. Saytdan foydalanishda davom etib, ularga rozilik bildirasiz.',
                'en' => 'We use cookies to make the site work better. By continuing to use the site you agree to them.',
            ],
            'cookie.accept' => ['ru' => 'Принять', 'uz' => 'Qabul qilish', 'en' => 'Accept'],
        ];
    }
}
