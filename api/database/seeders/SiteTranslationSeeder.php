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
            'tariffs.empty' => ['ru' => 'Тарифы не найдены', 'uz' => 'Tariflar topilmadi'],
            'tariffs.download' => ['ru' => 'Скачать', 'uz' => 'Yuklab olish'],
            'tariffs.archive_empty' => ['ru' => 'Архивных документов пока нет.', 'uz' => 'Hozircha arxiv hujjatlari yoʻq.'],
            'tariffs.back_to_list' => ['ru' => 'К действующим тарифам', 'uz' => 'Amaldagi tariflarga'],
            'tariffs.routers' => ['ru' => 'Роутеры', 'uz' => 'Routerlar'],
            'tariffs.all_tariffs' => ['ru' => 'Список тарифов', 'uz' => 'Tariflar roʻyxati'],
            'tariffs.nav_label' => ['ru' => 'Навигация по тарифам', 'uz' => 'Tariflar boʻyicha navigatsiya'],
            'common.breadcrumbs' => ['ru' => 'Хлебные крошки', 'uz' => 'Yoʻl belgilari'],
            'common.home' => ['ru' => 'Главная', 'uz' => 'Bosh sahifa'],
            'common.back_home' => ['ru' => 'На главную', 'uz' => 'Bosh sahifaga'],
            'common.store_prefix' => ['ru' => 'скачать в', 'uz' => 'yuklab olish'],
            'common.read_more' => ['ru' => 'Подробнее', 'uz' => 'Batafsil'],

            'faq.eyebrow' => ['ru' => 'Помощь и поддержка', 'uz' => 'Yordam va qoʻllab-quvvatlash'],
            'faq.title' => ['ru' => 'Часто<br />задаваемые <span class="color" data-color="accent">вопросы</span>', 'uz' => 'Koʻp<br />beriladigan <span class="color" data-color="accent">savollar</span>'],
            'faq.subtitle' => ['ru' => 'Ответы на популярные вопросы о подключении, тарифах, SIM/eSIM и личном кабинете — найдите нужный раздел или воспользуйтесь поиском.', 'uz' => 'Ulanish, tariflar, SIM/eSIM va shaxsiy kabinet haqidagi mashhur savollarga javoblar — kerakli boʻlimni toping yoki qidiruvdan foydalaning.'],
            'faq.search_placeholder' => ['ru' => 'Например: «как подключить eSIM», «не работает интернет»…', 'uz' => 'Masalan: «eSIM qanday ulanadi», «internet ishlamayapti»…'],
            'faq.search_label' => ['ru' => 'Поиск по вопросам', 'uz' => 'Savollar boʻyicha qidirish'],
            'faq.categories_label' => ['ru' => 'Категории вопросов', 'uz' => 'Savol toifalari'],
            'faq.all' => ['ru' => 'Все', 'uz' => 'Barchasi'],
            'faq.all_questions' => ['ru' => 'Все вопросы', 'uz' => 'Barcha savollar'],
            'faq.empty' => ['ru' => 'Ничего не найдено', 'uz' => 'Hech narsa topilmadi'],
            'faq.cta_title' => ['ru' => 'Не нашли ответ? Напишите в поддержку — ответим в течение дня.', 'uz' => 'Javob topolmadingizmi? Qoʻllab-quvvatlashga yozing — kun davomida javob beramiz.'],
            'faq.cta_button' => ['ru' => 'Связаться с поддержкой', 'uz' => 'Qoʻllab-quvvatlash bilan bogʻlanish'],

            'help.title' => ['ru' => 'Частые <span class="color" data-color="accent">вопросы</span>', 'uz' => 'Koʻp beriladigan <span class="color" data-color="accent">savollar</span>'],
            'help.aside_label' => ['ru' => 'Разделы помощи', 'uz' => 'Yordam boʻlimlari'],
            'help.aside_toggle' => ['ru' => 'Показать разделы помощи', 'uz' => 'Yordam boʻlimlarini koʻrsatish'],
            'help.nav_faq' => ['ru' => 'Часто задаваемые вопросы', 'uz' => 'Koʻp beriladigan savollar'],
            'help.nav_numbers' => ['ru' => 'Свободные номера', 'uz' => 'Boʻsh raqamlar'],
            'help.nav_contact' => ['ru' => 'Связь с нами', 'uz' => 'Biz bilan aloqa'],
            'help.all_questions' => ['ru' => 'Все вопросы и ответы', 'uz' => 'Barcha savol-javoblar'],

            'cdma.faq_title' => ['ru' => 'Частые вопросы', 'uz' => 'Koʻp beriladigan savollar'],

            'numbers.eyebrow' => ['ru' => 'Perfectum 5G — Мобильная связь', 'uz' => 'Perfectum 5G — Mobil aloqa'],
            'numbers.title' => ['ru' => 'Свободные<br /><span class="color" data-color="accent">номера</span>', 'uz' => 'Bo‘sh<br /><span class="color" data-color="accent">raqamlar</span>'],
            'numbers.subtitle' => ['ru' => 'Выберите понравившийся номер. Для приобретения обратитесь в офис или войдите в личный кабинет.', 'uz' => 'Yoqqan raqamni tanlang. Sotib olish uchun ofisga murojaat qiling yoki shaxsiy kabinetga kiring.'],
            'numbers.all' => ['ru' => 'Все', 'uz' => 'Hammasi'],
            'numbers.free' => ['ru' => 'Бесплатно', 'uz' => 'Bepul'],
            'numbers.currency' => ['ru' => 'сум', 'uz' => 'so‘m'],
            'numbers.empty' => ['ru' => 'Номера не найдены', 'uz' => 'Raqamlar topilmadi'],
            'numbers.page' => ['ru' => 'Страница', 'uz' => 'Sahifa'],
            'numbers.toggle_select' => ['ru' => 'Выбор номера', 'uz' => 'Raqam tanlash'],
            'numbers.toggle_mask' => ['ru' => 'По маске', 'uz' => 'Maska bo‘yicha'],
            'numbers.submit' => ['ru' => 'Подобрать', 'uz' => 'Tanlash'],
            'numbers.digit' => ['ru' => 'Цифра', 'uz' => 'Raqam'],

            'offices.eyebrow' => ['ru' => 'Где нас найти', 'uz' => 'Bizni qayerdan topasiz'],
            'offices.title' => ['ru' => 'Наши <span class="color" data-color="accent">офисы</span>', 'uz' => 'Bizning <span class="color" data-color="accent">ofislarimiz</span>'],
            'offices.subtitle' => ['ru' => 'Точки продаж, обслуживания и дилерская сеть Perfectum по всему Узбекистану.', 'uz' => 'Perfectum savdo va xizmat koʻrsatish nuqtalari hamda diler tarmogʻi butun Oʻzbekiston boʻylab.'],
            'offices.filter_label' => ['ru' => 'Фильтр офисов и дилеров', 'uz' => 'Ofis va dilerlar filtri'],
            'offices.type_label' => ['ru' => 'Тип точки', 'uz' => 'Nuqta turi'],
            'offices.search' => ['ru' => 'Поиск', 'uz' => 'Qidiruv'],
            'offices.search_placeholder' => ['ru' => 'Поиск по городу, адресу, названию...', 'uz' => 'Shahar, manzil, nom boʻyicha qidirish...'],
            'offices.all' => ['ru' => 'Все', 'uz' => 'Barchasi'],
            'offices.offices' => ['ru' => 'Офисы', 'uz' => 'Ofislar'],
            'offices.dealers' => ['ru' => 'Дилеры', 'uz' => 'Dilerlar'],
            'offices.office' => ['ru' => 'офис', 'uz' => 'ofis'],
            'offices.dealer' => ['ru' => 'Дилер', 'uz' => 'Diler'],
            'offices.offices_count' => ['ru' => 'Офисов', 'uz' => 'Ofis'],
            'offices.dealers_count' => ['ru' => 'Дилеров', 'uz' => 'Diler'],
            'offices.shown' => ['ru' => 'Показано', 'uz' => 'Koʻrsatilgan'],
            'offices.region' => ['ru' => 'Регион', 'uz' => 'Hudud'],
            'offices.all_regions' => ['ru' => 'Все регионы', 'uz' => 'Barcha hududlar'],
            'offices.city' => ['ru' => 'Город/кластер', 'uz' => 'Shahar/klaster'],
            'offices.all_cities' => ['ru' => 'Все города', 'uz' => 'Barcha shaharlar'],
            'offices.address' => ['ru' => 'Адрес', 'uz' => 'Manzil'],
            'offices.phone' => ['ru' => 'Номер', 'uz' => 'Raqam'],
            'offices.locate' => ['ru' => 'Найти ближайшие ко мне', 'uz' => 'Menga eng yaqinini topish'],
            'offices.locate_hint' => ['ru' => 'Местоположение не определено.', 'uz' => 'Joylashuv aniqlanmadi.'],
            'offices.locating' => ['ru' => 'Определяем ваше местоположение…', 'uz' => 'Joylashuvingiz aniqlanmoqda…'],
            'offices.located' => ['ru' => 'Показаны ближайшие к вам точки.', 'uz' => 'Sizga eng yaqin nuqtalar koʻrsatildi.'],
            'offices.locate_failed' => ['ru' => 'Не удалось определить местоположение. Разрешите доступ к геолокации.', 'uz' => 'Joylashuvni aniqlab boʻlmadi. Brauzerda geolokatsiyaga ruxsat bering.'],
            'offices.found' => ['ru' => 'Найденные точки', 'uz' => 'Topilgan nuqtalar'],
            'offices.empty' => ['ru' => 'Ничего не найдено', 'uz' => 'Hech narsa topilmadi'],
            'offices.pagination_label' => ['ru' => 'Страницы точек', 'uz' => 'Nuqtalar sahifalari'],
            'offices.map_label' => ['ru' => 'Карта офисов и дилеров Perfectum', 'uz' => 'Perfectum ofis va dilerlari xaritasi'],
            'offices.map_unavailable' => ['ru' => 'Карта временно недоступна. Список офисов и дилеров ниже работает как обычно.', 'uz' => 'Xarita vaqtincha ishlamayapti. Quyidagi ofis va dilerlar roʻyxati odatdagidek ishlaydi.'],
            'offices.route' => ['ru' => 'Построить маршрут', 'uz' => 'Marshrut qurish'],
            'offices.you_here' => ['ru' => 'Вы здесь', 'uz' => 'Siz shu yerdasiz'],

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
