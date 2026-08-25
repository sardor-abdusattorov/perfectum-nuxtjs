<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\SiteTranslation;
use Illuminate\Database\Seeder;

class SiteTranslationSeeder extends Seeder
{
    private const CATEGORY = 'app';

    /**
     * Keys the site has stopped asking for. The panel lets an admin add lines
     * of their own, so the seeder cannot simply keep whatever it does not
     * mention — it names the ones it retired and removes those.
     *
     * @var list<string>
     */
    private const RETIRED = [
        'cdma.tab_numbers',
    ];

    public function run(): void
    {
        foreach ($this->lines() as $key => $value) {
            SiteTranslation::updateOrCreate(
                ['category' => self::CATEGORY, 'key' => $key],
                ['value' => $value, 'is_published' => true]
            );
        }

        SiteTranslation::query()
            ->where('category', self::CATEGORY)
            ->whereIn('key', self::RETIRED)
            ->delete();
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
            'date.months' => [
                'ru' => 'Январь,Февраль,Март,Апрель,Май,Июнь,Июль,Август,Сентябрь,Октябрь,Ноябрь,Декабрь',
                'uz' => 'Yanvar,Fevral,Mart,Aprel,May,Iyun,Iyul,Avgust,Sentabr,Oktabr,Noyabr,Dekabr',
            ],
            'date.months_of' => [
                'ru' => 'января,февраля,марта,апреля,мая,июня,июля,августа,сентября,октября,ноября,декабря',
                'uz' => 'yanvar,fevral,mart,aprel,may,iyun,iyul,avgust,sentabr,oktabr,noyabr,dekabr',
            ],
            'date.long' => ['ru' => '{day} {month} {year}', 'uz' => '{day}-{month}, {year}'],

            'preview.notice' => ['ru' => 'Режим просмотра', 'uz' => 'Koʻrish rejimi'],
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

            'common.pagination' => ['ru' => 'Пагинация', 'uz' => 'Sahifalash'],
            'common.loading' => ['ru' => 'Загрузка…', 'uz' => 'Yuklanmoqda…'],

            'news.title' => ['ru' => 'Новости <span class="color" data-color="accent">Perfectum</span>', 'uz' => 'Perfectum <span class="color" data-color="accent">yangiliklari</span>'],
            'news.search_placeholder' => ['ru' => 'Поиск по новостям…', 'uz' => 'Yangiliklar boʻyicha qidirish…'],
            'news.search_label' => ['ru' => 'Поиск по новостям', 'uz' => 'Yangiliklar boʻyicha qidirish'],
            'news.categories_label' => ['ru' => 'Категории новостей', 'uz' => 'Yangilik toifalari'],
            'news.all' => ['ru' => 'Все', 'uz' => 'Barchasi'],
            'news.all_title' => ['ru' => 'Все новости', 'uz' => 'Barcha yangiliklar'],
            'news.read_full' => ['ru' => 'Читать полностью', 'uz' => 'Toʻliq oʻqish'],
            'news.back' => ['ru' => 'Назад к новостям', 'uz' => 'Yangiliklarga qaytish'],
            'news.empty' => ['ru' => 'Новостей пока нет', 'uz' => 'Hozircha yangiliklar yoʻq'],

            'actions.search_placeholder' => ['ru' => 'Поиск по акциям…', 'uz' => 'Aksiyalar boʻyicha qidirish…'],
            'actions.search_label' => ['ru' => 'Поиск по акциям', 'uz' => 'Aksiyalar boʻyicha qidirish'],
            'actions.categories_label' => ['ru' => 'Категории акций', 'uz' => 'Aksiya toifalari'],
            'actions.all' => ['ru' => 'Все', 'uz' => 'Barchasi'],
            'actions.all_title' => ['ru' => 'Все акции', 'uz' => 'Barcha aksiyalar'],
            'actions.until' => ['ru' => 'до', 'uz' => 'gacha:'],
            'actions.back' => ['ru' => 'Назад к акциям', 'uz' => 'Aksiyalarga qaytish'],
            'actions.empty' => ['ru' => 'Акций пока нет', 'uz' => 'Hozircha aksiyalar yoʻq'],

            'careers.eyebrow' => ['ru' => 'Работа в Perfectum', 'uz' => 'Perfectumda ish'],
            'careers.title' => ['ru' => 'Карьера в <span class="color" data-color="accent">Perfectum</span>', 'uz' => 'Perfectumda <span class="color" data-color="accent">karyera</span>'],
            'careers.subtitle' => ['ru' => 'Открытые позиции и жизнь внутри компании.', 'uz' => 'Ochiq lavozimlar va kompaniya ichidagi hayot.'],
            'careers.intro_title' => ['ru' => 'Строим первую сеть 5G Standalone в Центральной Азии — и нам нужны амбициозные люди.', 'uz' => 'Markaziy Osiyodagi birinchi 5G Standalone tarmogʻini quryapmiz — bizga gʻayratli insonlar kerak.'],
            'careers.intro_text' => ['ru' => 'Открытые позиции в инженерии, продукте, коммерции и поддержке клиентов.', 'uz' => 'Muhandislik, mahsulot, tijorat va mijozlarni qoʻllab-quvvatlash boʻyicha ochiq lavozimlar.'],
            'careers.empty' => ['ru' => 'Открытых вакансий пока нет', 'uz' => 'Hozircha ochiq vakansiyalar yoʻq'],
            'careers.back' => ['ru' => 'Вернуться к вакансиям', 'uz' => 'Vakansiyalarga qaytish'],
            'careers.cta_line' => ['ru' => 'Присоединяйтесь к нашей команде и развивайтесь вместе с нами!', 'uz' => 'Jamoamizga qoʻshiling va biz bilan birga rivojlaning!'],
            'careers.cta_contacts' => ['ru' => 'Отправляйте резюме уже сегодня на <a class="vacancy__mail" href="mailto:hr@perfectum.uz">e-mail</a> или звоните по тел: <b>+998 90 305 12 21</b>', 'uz' => 'Rezyumeni bugunoq <a class="vacancy__mail" href="mailto:hr@perfectum.uz">e-mail</a> orqali yuboring yoki qoʻngʻiroq qiling: <b>+998 90 305 12 21</b>'],

            'procurement.heading' => ['ru' => 'Актуальные закупки', 'uz' => 'Dolzarb xaridlar'],
            'procurement.state_open' => ['ru' => 'Открыт', 'uz' => 'Ochiq'],
            'procurement.state_closed' => ['ru' => 'Закрыт', 'uz' => 'Yopiq'],
            'procurement.empty' => ['ru' => 'Активных закупок пока нет', 'uz' => 'Hozircha faol xaridlar yoʻq'],
            'procurement.back' => ['ru' => 'Назад к закупкам', 'uz' => 'Xaridlarga qaytish'],
            'procurement.deadline' => ['ru' => 'Приём заявок до', 'uz' => 'Arizalar qabul qilinadi:'],
            'procurement.files' => ['ru' => 'Документы', 'uz' => 'Hujjatlar'],

            'cdma.news_title' => ['ru' => 'Новости', 'uz' => 'Yangiliklar'],
            'cdma.services_title' => ['ru' => 'Услуги', 'uz' => 'Xizmatlar'],
            'cdma.tariffs_title' => ['ru' => 'Тарифы', 'uz' => 'Tariflar'],
            'cdma.more' => ['ru' => 'Подробнее', 'uz' => 'Batafsil'],
            'cdma.tariff_includes' => ['ru' => 'Что входит', 'uz' => 'Nimalar kiradi'],
            'cdma.connect' => ['ru' => 'Подключиться', 'uz' => 'Ulanish'],
            'cdma.all_tariffs' => ['ru' => 'Все тарифы', 'uz' => 'Barcha tariflar'],
            'cdma.all_news' => ['ru' => 'Все новости', 'uz' => 'Barcha yangiliklar'],
            'cdma.numbers_title' => ['ru' => 'Свободные номера', 'uz' => 'Boʻsh raqamlar'],
            'cdma.tab_dealers' => ['ru' => 'Дилеры', 'uz' => 'Dilerlar'],
            'cdma.sections_label' => ['ru' => 'Разделы CDMA', 'uz' => 'CDMA boʻlimlari'],
            'cdma.to_home' => ['ru' => 'На главную', 'uz' => 'Bosh sahifaga'],
            'cdma.copyright' => ['ru' => 'ООО «RWC» (Торговая марка Perfectum)', 'uz' => '«RWC» MChJ (Perfectum savdo belgisi)'],
            'cdma.support_title' => ['ru' => 'Поддержка', 'uz' => 'Qoʻllab-quvvatlash'],
            'cdma.promo_title' => ['ru' => 'Акции', 'uz' => 'Aksiyalar'],
            'cdma.promo_badge' => ['ru' => 'Акция', 'uz' => 'Aksiya'],
            'cdma.promo_active' => ['ru' => 'Действует', 'uz' => 'Amalda'],

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

            'help.numbers_title' => ['ru' => 'Свободные <span class="color" data-color="accent">номера</span>', 'uz' => 'Boʻsh <span class="color" data-color="accent">raqamlar</span>'],
            'help.numbers_prefix' => ['ru' => 'Префикс', 'uz' => 'Prefiks'],
            'help.numbers_price' => ['ru' => 'Цена', 'uz' => 'Narx'],
            'help.numbers_number' => ['ru' => 'Номер', 'uz' => 'Raqam'],
            'help.numbers_invalid' => ['ru' => 'Введите от 1 до 4 цифр номера', 'uz' => 'Raqamning 1 tadan 4 tagacha raqamini kiriting'],
            'help.numbers_all_prefixes' => ['ru' => 'Все префиксы', 'uz' => 'Barcha prefikslar'],
            'help.numbers_all_prices' => ['ru' => 'Все цены', 'uz' => 'Barcha narxlar'],
            'help.numbers_free' => ['ru' => 'Все бесплатные', 'uz' => 'Barcha bepul raqamlar'],
            'help.numbers_search' => ['ru' => 'Найти номер', 'uz' => 'Raqamni qidirish'],
            'help.numbers_note' => ['ru' => 'Для выбора номера мобильной связи <span class="color" data-color="accent">5G Standalone</span> с префиксом <span class="color" data-color="accent">+998 80</span>', 'uz' => '<span class="color" data-color="accent">5G Standalone</span> tarmogʻi uchun <span class="color" data-color="accent">+998 80</span> prefiksli mobil aloqa raqamini tanlash uchun'],
            'help.numbers_note_link' => ['ru' => 'нажмите здесь', 'uz' => 'bu yerni bosing'],
            'help.numbers_empty' => ['ru' => 'По выбранным параметрам на данный момент свободных номеров нет', 'uz' => 'Siz tanlagan parametrlar boʻyicha hozircha boʻsh raqamlar yoʻq'],
            'help.numbers_col_number' => ['ru' => 'Номер', 'uz' => 'Raqam'],
            'help.numbers_col_prefix' => ['ru' => 'Префикс', 'uz' => 'Prefiks'],
            'help.numbers_col_price' => ['ru' => 'Цена', 'uz' => 'Narx'],
            'help.numbers_col_date' => ['ru' => 'Дата', 'uz' => 'Sana'],

            'help.contact_title' => ['ru' => 'Связь <span class="color" data-color="accent">с нами</span>', 'uz' => 'Biz bilan <span class="color" data-color="accent">aloqa</span>'],
            'help.contact_form_title' => ['ru' => 'Оставьте заявку на подключение', 'uz' => 'Ulanish uchun ariza qoldiring'],
            'help.contact_phone' => ['ru' => 'Ваш номер телефона', 'uz' => 'Telefon raqamingiz'],
            'help.contact_theme' => ['ru' => 'Тема обращения', 'uz' => 'Murojaat mavzusi'],
            'help.contact_theme_placeholder' => ['ru' => 'Выберите тему', 'uz' => 'Mavzuni tanlang'],
            'help.contact_message' => ['ru' => 'Чем можем помочь?', 'uz' => 'Qanday yordam bera olamiz?'],
            'help.contact_message_placeholder' => ['ru' => 'Введите текст…', 'uz' => 'Matnni kiriting…'],
            'help.contact_counter' => ['ru' => 'Символов осталось', 'uz' => 'Qolgan belgilar'],
            'help.contact_submit' => ['ru' => 'Отправить', 'uz' => 'Yuborish'],
            'help.contact_agree' => ['ru' => 'Нажимая «Отправить», вы соглашаетесь с <a>политикой обработки персональных данных</a>. Мы свяжемся с вами в течение рабочего дня.', 'uz' => '«Yuborish» tugmasini bosib, siz <a>shaxsiy maʼlumotlarni qayta ishlash siyosatiga</a> rozilik bildirasiz. Ish kuni davomida siz bilan bogʻlanamiz.'],
            'help.contact_required' => ['ru' => 'Обязательное поле', 'uz' => 'Majburiy maydon'],
            'help.contact_success' => ['ru' => 'Заявка отправлена! Мы свяжемся с вами в течение рабочего дня.', 'uz' => 'Ariza yuborildi! Ish kuni davomida siz bilan bogʻlanamiz.'],
            'help.contact_error' => ['ru' => 'Не удалось отправить заявку. Попробуйте ещё раз.', 'uz' => 'Arizani yuborib boʻlmadi. Qayta urinib koʻring.'],

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
            'offices.dealers_count' => ['ru' => 'дилеров', 'uz' => 'diler'],
            'offices.dealers_empty' => ['ru' => 'Список дилеров пока не заполнен', 'uz' => 'Dilerlar roʻyxati hali toʻldirilmagan'],
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

            'company.nav_label' => ['ru' => 'Разделы о компании', 'uz' => 'Kompaniya boʻlimlari'],
            'documents.download' => ['ru' => 'Скачать', 'uz' => 'Yuklab olish'],
            'documents.empty' => ['ru' => 'Документы пока не загружены', 'uz' => 'Hujjatlar hali yuklanmagan'],

            'coverage.search_label' => ['ru' => 'Проверьте покрытие в вашем городе', 'uz' => 'Shahringizdagi qamrovni tekshiring'],
            'coverage.city' => ['ru' => 'Город', 'uz' => 'Shahar'],
            'coverage.network' => ['ru' => 'Тип сети', 'uz' => 'Tarmoq turi'],
            'coverage.map_label' => ['ru' => 'Карта покрытия сети Perfectum', 'uz' => 'Perfectum tarmogʻi qamrov xaritasi'],
            'coverage.map_unavailable' => ['ru' => 'Карта временно недоступна. Проверить покрытие можно у оператора поддержки.', 'uz' => 'Xarita vaqtincha ishlamayapti. Qamrovni qoʻllab-quvvatlash operatoridan bilib olishingiz mumkin.'],
            'coverage.locate' => ['ru' => 'Моё местоположение', 'uz' => 'Mening joylashuvim'],
            'coverage.locating' => ['ru' => 'Определяем ваше местоположение…', 'uz' => 'Joylashuvingiz aniqlanmoqda…'],
            'coverage.locate_failed' => ['ru' => 'Не удалось определить местоположение. Разрешите доступ к геолокации.', 'uz' => 'Joylashuvni aniqlab boʻlmadi. Brauzerda geolokatsiyaga ruxsat bering.'],
            'coverage.you_here' => ['ru' => 'Вы здесь', 'uz' => 'Siz shu yerdasiz'],
            'coverage.fullscreen' => ['ru' => 'Во весь экран', 'uz' => 'Butun ekranga'],
            'coverage.exit_fullscreen' => ['ru' => 'Выйти из полноэкранного режима', 'uz' => 'Butun ekran rejimidan chiqish'],
            'coverage.zoom_in' => ['ru' => 'Приблизить', 'uz' => 'Yaqinlashtirish'],
            'coverage.zoom_out' => ['ru' => 'Отдалить', 'uz' => 'Uzoqlashtirish'],
            'coverage.empty' => ['ru' => 'Зоны покрытия пока не загружены', 'uz' => 'Qamrov hududlari hali yuklanmagan'],
            'coverage.address' => ['ru' => 'Поиск по адресу', 'uz' => 'Manzil boʻyicha qidirish'],
            'coverage.address_placeholder' => ['ru' => 'Введите адрес', 'uz' => 'Manzilni kiriting'],
            'coverage.address_clear' => ['ru' => 'Очистить', 'uz' => 'Tozalash'],
            'coverage.address_not_found' => ['ru' => 'Адрес не найден. Уточните улицу и город.', 'uz' => 'Manzil topilmadi. Koʻcha va shaharni aniqlashtiring.'],

            'devices.hero_eyebrow' => ['ru' => 'Проверка совместимости', 'uz' => 'Moslikni tekshirish'],
            'devices.hero_title' => [
                'ru' => 'Каталог<br /><span class="page-hero__title-red">устройств</span>',
                'uz' => 'Qurilmalar<br /><span class="page-hero__title-red">katalogi</span>',
            ],
            'devices.hero_subtitle' => [
                'ru' => 'Роутеры для домашнего интернета 5G SA и список смартфонов, уже получивших поддержку сети 5G Standalone.',
                'uz' => '5G SA uy interneti uchun routerlar va 5G Standalone tarmogʻini qoʻllab-quvvatlaydigan smartfonlar roʻyxati.',
            ],
            'devices.hero_brand_title' => [
                'ru' => 'Устройства<br /><span class="page-hero__title-red">{brand}</span>',
                'uz' => '{brand}<br /><span class="page-hero__title-red">qurilmalari</span>',
            ],
            'devices.hero_brand_subtitle' => [
                'ru' => 'Модели {brand}, проверенные и официально подтверждённые для работы в сети Perfectum.',
                'uz' => 'Perfectum tarmogʻida ishlashi tekshirilgan va tasdiqlangan {brand} modellari.',
            ],
            'devices.tabs_label' => ['ru' => 'Категории устройств', 'uz' => 'Qurilma toifalari'],
            'devices.in_stock' => ['ru' => 'Сегодня в продаже', 'uz' => 'Bugun sotuvda'],
            'devices.callout_5g' => [
                'ru' => '<b>Устройства для сети 5G Standalone (5G SA).</b> 5G SA — наша текущая технология, первая полностью самостоятельная сеть 5-го поколения в Центральной Азии. В отличие от CDMA, для подключения подходит большинство современных смартфонов с поддержкой 5G — отдельная проверка совместимости, как правило, не требуется.',
                'uz' => '<b>5G Standalone (5G SA) tarmogʻi uchun qurilmalar.</b> 5G SA — bizning joriy texnologiyamiz, Markaziy Osiyodagi birinchi toʻliq mustaqil 5-avlod tarmogʻi. CDMAdan farqli oʻlaroq, ulanish uchun 5G-ni qoʻllab-quvvatlaydigan zamonaviy smartfonlarning aksariyati mos keladi — alohida moslik tekshiruvi odatda talab qilinmaydi.',
            ],
            'devices.callout_cdma' => [
                'ru' => '<b>Устройства для сети CDMA.</b> Телефоны и модемы, проверенные на совместимость с сетью Perfectum CDMA. Устройства других производителей могут не поддерживать частоты сети — перед покупкой сверьтесь со списком.',
                'uz' => '<b>CDMA tarmogʻi uchun qurilmalar.</b> Perfectum CDMA tarmogʻiga mosligi tekshirilgan telefonlar va modemlar. Boshqa ishlab chiqaruvchilarning qurilmalari tarmoq chastotalarini qoʻllab-quvvatlamasligi mumkin — xarid oldidan roʻyxat bilan solishtiring.',
            ],
            'devices.model' => ['ru' => 'Модель', 'uz' => 'Model'],
            'devices.models_count' => ['ru' => 'Моделей: {n}', 'uz' => 'Modellar: {n}'],
            'devices.all_brands' => ['ru' => 'Все бренды', 'uz' => 'Barcha brendlar'],
            'devices.note' => [
                'ru' => 'Список протестированных и официально подтверждённых моделей будет дополняться по мере расширения сети 5G SA.',
                'uz' => 'Sinovdan oʻtgan va rasman tasdiqlangan modellar roʻyxati 5G SA tarmogʻi kengaygan sari toʻldirib boriladi.',
            ],
            'devices.note_brand' => [
                'ru' => 'Список моделей пополняется по мере сертификации устройств в сети Perfectum.',
                'uz' => 'Modellar roʻyxati qurilmalar Perfectum tarmogʻida sertifikatlanishi bilan toʻldiriladi.',
            ],
            'devices.empty' => ['ru' => 'Устройства не найдены', 'uz' => 'Qurilmalar topilmadi'],
            'devices.buy' => ['ru' => 'Купить', 'uz' => 'Sotib olish'],
            'devices.installment' => ['ru' => 'Рассрочка', 'uz' => 'Boʻlib toʻlash'],
            'devices.installment_months' => ['ru' => 'мес.', 'uz' => 'oy'],
            'devices.installment_monthly' => ['ru' => 'Платёж в месяц', 'uz' => 'Oylik toʻlov'],
            'devices.installment_total' => ['ru' => 'Общая сумма', 'uz' => 'Umumiy summa'],
            'devices.installment_term' => ['ru' => 'Срок рассрочки', 'uz' => 'Boʻlib toʻlash muddati'],
            'devices.installment_empty' => ['ru' => 'Вариантов рассрочки пока нет', 'uz' => 'Hozircha boʻlib toʻlash imkoniyatlari yoʻq'],
            'devices.specs_title' => ['ru' => 'Характеристики', 'uz' => 'Texnik xususiyatlar'],
            'devices.currency' => ['ru' => 'сум', 'uz' => 'soʻm'],
            'devices.pager_label' => ['ru' => 'Другие устройства', 'uz' => 'Boshqa qurilmalar'],

            'services.hero_eyebrow' => ['ru' => 'Каталог сервисов', 'uz' => 'Xizmatlar katalogi'],
            'services.hero_title' => [
                'ru' => 'Все <span class="page-hero__title-red">услуги</span><br>в одном месте',
                'uz' => 'Barcha <span class="page-hero__title-red">xizmatlar</span><br>bir joyda',
            ],
            'services.hero_subtitle' => [
                'ru' => 'Мобильный кабинет, лояльность, дополнительные сервисы и услуги для абонентов домашнего интернета — выберите нужную категорию или найдите услугу по названию.',
                'uz' => 'Mobil kabinet, sodiqlik dasturi, qoʻshimcha servislar va uy interneti abonentlari uchun xizmatlar — kerakli toifani tanlang yoki xizmatni nomi boʻyicha toping.',
            ],
            'services.catalog_heading' => ['ru' => 'Каталог услуг Perfectum', 'uz' => 'Perfectum xizmatlar katalogi'],
            'services.search_placeholder' => [
                'ru' => 'Найти услугу: “статический IP”, “баланс”, “роуминг”…',
                'uz' => 'Xizmat topish: “statik IP”, “balans”, “rouming”…',
            ],
            'services.search_label' => ['ru' => 'Поиск по услугам', 'uz' => 'Xizmatlar boʻyicha qidirish'],
            'services.count' => ['ru' => 'Услуг: {n}', 'uz' => 'Xizmatlar: {n}'],
            'services.empty' => ['ru' => 'Услуги не найдены', 'uz' => 'Xizmatlar topilmadi'],
            'services.support_title' => [
                'ru' => 'Не нашли нужную услугу? Менеджеры поддержки помогут подобрать решение под ваши задачи.',
                'uz' => 'Kerakli xizmatni topmadingizmi? Qoʻllab-quvvatlash menejerlari vazifangizga mos yechim tanlashga yordam beradi.',
            ],
            'services.support_btn' => ['ru' => 'Связаться с поддержкой', 'uz' => 'Qoʻllab-quvvatlash bilan bogʻlanish'],
            'services.price_label' => ['ru' => 'Стоимость', 'uz' => 'Narxi'],
            'services.ussd_label' => ['ru' => 'USSD-команда', 'uz' => 'USSD buyrugʻi'],
            'services.steps_title' => ['ru' => 'Как подключить', 'uz' => 'Qanday ulash mumkin'],
            'services.description_title' => ['ru' => 'Подробное описание', 'uz' => 'Batafsil tavsif'],
            'services.all_services' => ['ru' => 'Все услуги', 'uz' => 'Barcha xizmatlar'],

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
            'seo.service' => ['ru' => 'Услуга', 'uz' => 'Xizmat'],
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
