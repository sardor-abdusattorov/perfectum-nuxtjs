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
            'header.account' => ['ru' => 'Личный кабинет', 'uz' => 'Shaxsiy kabinet'],
            'header.open_menu' => ['ru' => 'Открыть меню', 'uz' => 'Menyuni ochish'],
            'header.close_menu' => ['ru' => 'Закрыть', 'uz' => 'Yopish'],

            'footer.office_title' => ['ru' => 'Головной офис', 'uz' => 'Bosh ofis'],
            'footer.address' => [
                'ru' => 'Узбекистан, Ташкент, ул. Тараса Шевченко 21',
                'uz' => 'Oʻzbekiston, Toshkent, Taras Shevchenko koʻchasi 21',
            ],
            'footer.map_link' => ['ru' => 'Показать на карте', 'uz' => 'Xaritada koʻrsatish'],
            'footer.phones_title' => ['ru' => 'Телефоны поддержки', 'uz' => 'Qoʻllab-quvvatlash telefonlari'],
            'footer.phone_short_note' => [
                'ru' => 'Для абонентов Perfectum',
                'uz' => 'Perfectum abonentlari uchun',
            ],
            'footer.telegram_title' => [
                'ru' => 'Чат с оператором в Telegram',
                'uz' => 'Telegramda operator bilan chat',
            ],
            'footer.email_title' => ['ru' => 'Email', 'uz' => 'Email'],
            'footer.email_info_note' => [
                'ru' => 'Для получения информации',
                'uz' => 'Maʼlumot olish uchun',
            ],
            'footer.email_hotline_note' => [
                'ru' => 'Горячая линия для жалоб и обращений',
                'uz' => 'Shikoyat va murojaatlar uchun ishonch telefoni',
            ],
            'footer.nav_label' => [
                'ru' => 'Навигация в подвале',
                'uz' => 'Pastki qismdagi navigatsiya',
            ],
            'footer.copyright' => [
                'ru' => 'ООО «RWC» (Торговая марка Perfectum) | Все права защищены | Услуги лицензированы | Цены указаны с учетом всех налогов.',
                'uz' => '«RWC» MChJ (Perfectum savdo belgisi) | Barcha huquqlar himoyalangan | Xizmatlar litsenziyalangan | Narxlar barcha soliqlar bilan koʻrsatilgan.',
            ],
            'footer.app_title' => ['ru' => 'Мобильное приложение', 'uz' => 'Mobil ilova'],
            'footer.socials_title' => ['ru' => 'Социальные сети', 'uz' => 'Ijtimoiy tarmoqlar'],

            'tariff.connect_anyway' => [
                'ru' => 'Подключить любым способом',
                'uz' => 'Tarifni istalgan usulda ulashingiz mumkin',
            ],
            'tariff.close' => ['ru' => 'Закрыть', 'uz' => 'Yopish'],

            'tariffs.eyebrow' => ['ru' => 'Выбери своё', 'uz' => 'Oʻzingiznikini tanlang'],
            'tariffs.title' => ['ru' => 'Тарифы <span class="color" data-color="accent">5G</span>', 'uz' => '<span class="color" data-color="accent">5G</span> tariflar'],
            'tariffs.categories_label' => ['ru' => 'Категории услуг', 'uz' => 'Xizmat toifalari'],
            'tariffs.types_label' => ['ru' => 'Типы тарифов', 'uz' => 'Tarif turlari'],
            'tariffs.all' => ['ru' => 'Все', 'uz' => 'Barchasi'],
            'tariffs.connect' => ['ru' => 'Подключить', 'uz' => 'Ulanish'],
            'tariffs.archive' => ['ru' => 'Архивные тарифы', 'uz' => 'Arxiv tariflar'],
            'common.prev' => ['ru' => 'Назад', 'uz' => 'Orqaga'],
            'common.next' => ['ru' => 'Вперёд', 'uz' => 'Oldinga'],
            'common.back_home' => ['ru' => 'На главную', 'uz' => 'Bosh sahifaga'],
            'common.store_prefix' => ['ru' => 'скачать в', 'uz' => 'yuklab olish'],
            'common.read_more' => ['ru' => 'Подробнее', 'uz' => 'Batafsil'],

            'seo.about' => ['ru' => 'О компании', 'uz' => 'Kompaniya haqida'],
            'seo.actions' => ['ru' => 'Акции', 'uz' => 'Aksiyalar'],
            'seo.action' => ['ru' => 'Акция', 'uz' => 'Aksiya'],
            'seo.careers' => ['ru' => 'Карьера', 'uz' => 'Karyera'],
            'seo.vacancy' => ['ru' => 'Вакансия', 'uz' => 'Vakansiya'],
            'seo.contacts' => ['ru' => 'Контакты', 'uz' => 'Kontaktlar'],
            'seo.coverage' => ['ru' => 'Карта покрытия', 'uz' => 'Qamrov xaritasi'],
            'seo.devices' => ['ru' => 'Каталог устройств', 'uz' => 'Qurilmalar katalogi'],
            'seo.device' => ['ru' => 'Устройство', 'uz' => 'Qurilma'],
            'seo.documents' => ['ru' => 'Документы', 'uz' => 'Hujjatlar'],
            'seo.faq' => ['ru' => 'Часто задаваемые вопросы', 'uz' => 'Koʻp beriladigan savollar'],
            'seo.help' => ['ru' => 'Помощь', 'uz' => 'Yordam'],
            'seo.help_contact' => ['ru' => 'Связаться с нами', 'uz' => 'Biz bilan bogʻlanish'],
            'seo.news' => ['ru' => 'Новости', 'uz' => 'Yangiliklar'],
            'seo.news_item' => ['ru' => 'Новость', 'uz' => 'Yangilik'],
            'seo.numbers' => ['ru' => 'Свободные номера', 'uz' => 'Boʻsh raqamlar'],
            'seo.offices' => ['ru' => 'Офисы продаж', 'uz' => 'Sotuv ofislari'],
            'seo.procurement' => ['ru' => 'Закупки', 'uz' => 'Xaridlar'],
            'seo.tender' => ['ru' => 'Закупка', 'uz' => 'Xarid'],
            'seo.services' => ['ru' => 'Услуги', 'uz' => 'Xizmatlar'],
            'seo.tariffs' => ['ru' => 'Тарифы', 'uz' => 'Tariflar'],
            'seo.tariffs_archive' => ['ru' => 'Архив тарифов', 'uz' => 'Tariflar arxivi'],
            'seo.tariff' => ['ru' => 'Тариф', 'uz' => 'Tarif'],

            'seo.cdma' => ['ru' => 'CDMA', 'uz' => 'CDMA'],
            'seo.cdma_actions' => ['ru' => 'Акции CDMA', 'uz' => 'CDMA aksiyalari'],
            'seo.cdma_connect' => ['ru' => 'Как подключиться', 'uz' => 'Qanday ulanish kerak'],
            'seo.cdma_dealers' => ['ru' => 'Дилеры CDMA', 'uz' => 'CDMA dilerlari'],
            'seo.cdma_news' => ['ru' => 'Новости CDMA', 'uz' => 'CDMA yangiliklari'],
            'seo.cdma_service' => ['ru' => 'Услуга CDMA', 'uz' => 'CDMA xizmati'],
            'seo.cdma_tariff' => ['ru' => 'Тариф CDMA', 'uz' => 'CDMA tarifi'],

            'cookie.text' => [
                'ru' => 'Мы используем файлы cookie, чтобы сайт работал лучше. Продолжая пользоваться сайтом, вы соглашаетесь с их использованием.',
                'uz' => 'Sayt yaxshiroq ishlashi uchun cookie fayllaridan foydalanamiz. Saytdan foydalanishda davom etib, ularga rozilik bildirasiz.',
            ],
            'cookie.accept' => ['ru' => 'Принять', 'uz' => 'Qabul qilish'],
        ];
    }
}
