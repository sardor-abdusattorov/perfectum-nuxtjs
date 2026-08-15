<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PageKey;
use App\Models\PageSettings;
use Illuminate\Database\Seeder;

class PageSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $templates = $this->templates();

        foreach (PageKey::cases() as $key) {
            $template = $templates[$key->value] ?? [];

            PageSettings::updateOrCreate(['key' => $key->value], [
                'meta_title' => $template['title'] ?? null,
                'meta_description' => $template['description'] ?? null,
                'meta_keywords' => $template['keywords'] ?? null,
                'is_indexed' => true,
            ]);
        }
    }

    /**
     * On the tariff and news pages `{name}` stands for the record's own title;
     * the frontend substitutes it before the tag is rendered.
     *
     * @return array<string, array{title: array<string, string>, description: array<string, string>, keywords: array<string, string>}>
     */
    private function templates(): array
    {
        return [
            'home' => [
                'title' => [
                    'ru' => 'Perfectum — оператор связи 5G Standalone в Узбекистане',
                    'uz' => 'Perfectum — Oʻzbekistondagi 5G Standalone aloqa operatori',
                ],
                'description' => [
                    'ru' => 'Perfectum — высокоскоростной мобильный и домашний интернет на базе сети 5G Standalone. Тарифы, красивые номера и покрытие по всему Узбекистану.',
                    'uz' => 'Perfectum — 5G Standalone tarmogʻi asosidagi yuqori tezlikdagi mobil va uy interneti. Tariflar, chiroyli raqamlar va butun Oʻzbekiston boʻylab qamrov.',
                ],
                'keywords' => [
                    'ru' => 'Perfectum, 5G, интернет, мобильная связь, тарифы, Узбекистан',
                    'uz' => 'Perfectum, 5G, internet, mobil aloqa, tariflar, Oʻzbekiston',
                ],
            ],

            'tariffs' => [
                'title' => [
                    'ru' => 'Тарифы Perfectum — мобильная связь и 5G интернет',
                    'uz' => 'Perfectum tariflari — mobil aloqa va 5G internet',
                ],
                'description' => [
                    'ru' => 'Выберите тариф Perfectum с быстрым интернетом, звонками и выгодными условиями для общения по Узбекистану.',
                    'uz' => 'Tez internet, qoʻngʻiroqlar va qulay shartlarga ega Perfectum tarifini tanlang.',
                ],
                'keywords' => [
                    'ru' => 'тарифы Perfectum, 5G интернет, мобильная связь, интернет Узбекистан',
                    'uz' => 'Perfectum tariflari, 5G internet, mobil aloqa, Oʻzbekiston interneti',
                ],
            ],

            'tariff' => [
                'title' => [
                    'ru' => '{name} — тариф 5G интернет | Perfectum',
                    'uz' => '{name} — 5G internet tarifi | Perfectum',
                ],
                'description' => [
                    'ru' => 'Подключите тариф {name} от Perfectum: интернет, звонки и современные возможности связи по Узбекистану.',
                    'uz' => 'Perfectum’dan {name} tarifini ulang: internet, qoʻngʻiroqlar va zamonaviy aloqa imkoniyatlari.',
                ],
                'keywords' => [
                    'ru' => '{name}, тариф Perfectum, 5G тариф, мобильный интернет',
                    'uz' => '{name}, Perfectum tarifi, 5G tarif, mobil internet',
                ],
            ],

            'cdma' => [
                'title' => [
                    'ru' => 'Домашний интернет Perfectum — высокоскоростной 5G интернет',
                    'uz' => 'Perfectum uy interneti — yuqori tezlikdagi 5G internet',
                ],
                'description' => [
                    'ru' => 'Подключите домашний интернет Perfectum с высокой скоростью и стабильным соединением на базе сети 5G.',
                    'uz' => '5G tarmogʻi asosidagi yuqori tezlik va barqaror ulanishga ega Perfectum uy internetini ulang.',
                ],
                'keywords' => [
                    'ru' => 'домашний интернет, 5G интернет, Perfectum, интернет Узбекистан',
                    'uz' => 'uy interneti, 5G internet, Perfectum, Oʻzbekiston interneti',
                ],
            ],

            'numbers' => [
                'title' => [
                    'ru' => 'Красивые номера Perfectum — выбрать номер онлайн',
                    'uz' => 'Perfectum chiroyli raqamlari — raqamni onlayn tanlang',
                ],
                'description' => [
                    'ru' => 'Выберите красивый номер Perfectum: золотые, VIP и легко запоминающиеся комбинации.',
                    'uz' => 'Perfectum chiroyli raqamini tanlang: oltin, VIP va oson esda qoladigan kombinatsiyalar.',
                ],
                'keywords' => [
                    'ru' => 'красивые номера, VIP номер, золотые номера, номер Perfectum',
                    'uz' => 'chiroyli raqamlar, VIP raqam, oltin raqamlar, Perfectum raqami',
                ],
            ],

            'services' => [
                'title' => [
                    'ru' => 'Услуги Perfectum — дополнительные возможности связи',
                    'uz' => 'Perfectum xizmatlari — qoʻshimcha aloqa imkoniyatlari',
                ],
                'description' => [
                    'ru' => 'Подключайте услуги Perfectum: переадресация, интернет-пакеты и другие сервисы связи.',
                    'uz' => 'Perfectum xizmatlarini ulang: qayta yoʻnaltirish, internet-paketlar va boshqa aloqa servislari.',
                ],
                'keywords' => [
                    'ru' => 'услуги Perfectum, переадресация, интернет услуги',
                    'uz' => 'Perfectum xizmatlari, qayta yoʻnaltirish, internet xizmatlari',
                ],
            ],

            'coverage_area' => [
                'title' => [
                    'ru' => 'Карта покрытия Perfectum — сеть 5G в Узбекистане',
                    'uz' => 'Perfectum qamrov xaritasi — Oʻzbekistondagi 5G tarmogʻi',
                ],
                'description' => [
                    'ru' => 'Проверьте покрытие сети Perfectum и доступность 5G интернета в вашем регионе.',
                    'uz' => 'Perfectum tarmogʻi qamrovini va hududingizda 5G internet mavjudligini tekshiring.',
                ],
                'keywords' => [
                    'ru' => 'карта покрытия, 5G покрытие, Perfectum интернет',
                    'uz' => 'qamrov xaritasi, 5G qamrov, Perfectum internet',
                ],
            ],

            'news' => [
                'title' => [
                    'ru' => 'Новости Perfectum — события и обновления компании',
                    'uz' => 'Perfectum yangiliklari — kompaniya voqealari va yangilanishlari',
                ],
                'description' => [
                    'ru' => 'Читайте новости Perfectum: новые тарифы, запуск услуг, акции и обновления компании.',
                    'uz' => 'Perfectum yangiliklarini oʻqing: yangi tariflar, xizmatlar ishga tushirilishi, aksiyalar va kompaniya yangilanishlari.',
                ],
                'keywords' => [
                    'ru' => 'новости Perfectum, акции, тарифы, мобильная связь',
                    'uz' => 'Perfectum yangiliklari, aksiyalar, tariflar, mobil aloqa',
                ],
            ],

            'news_item' => [
                'title' => [
                    'ru' => '{name} | Новости Perfectum',
                    'uz' => '{name} | Perfectum yangiliklari',
                ],
                'description' => [
                    'ru' => 'Читайте новости Perfectum: новые тарифы, запуск услуг, акции и обновления компании.',
                    'uz' => 'Perfectum yangiliklarini oʻqing: yangi tariflar, xizmatlar ishga tushirilishi, aksiyalar va kompaniya yangilanishlari.',
                ],
                'keywords' => [
                    'ru' => 'новости Perfectum, акции, тарифы, мобильная связь',
                    'uz' => 'Perfectum yangiliklari, aksiyalar, tariflar, mobil aloqa',
                ],
            ],

            'actions' => [
                'title' => [
                    'ru' => 'Акции Perfectum — бонусы и специальные предложения',
                    'uz' => 'Perfectum aksiyalari — bonuslar va maxsus takliflar',
                ],
                'description' => [
                    'ru' => 'Актуальные акции Perfectum: бонусы, скидки и специальные предложения для абонентов.',
                    'uz' => 'Perfectum’ning dolzarb aksiyalari: abonentlar uchun bonuslar, chegirmalar va maxsus takliflar.',
                ],
                'keywords' => [
                    'ru' => 'акции Perfectum, бонусы, скидки, интернет акции',
                    'uz' => 'Perfectum aksiyalari, bonuslar, chegirmalar, internet aksiyalari',
                ],
            ],

            'faq' => [
                'title' => [
                    'ru' => 'Вопросы и ответы Perfectum',
                    'uz' => 'Perfectum savol-javoblari',
                ],
                'description' => [
                    'ru' => 'Ответы на популярные вопросы о тарифах, интернете, услугах и мобильной связи Perfectum.',
                    'uz' => 'Perfectum tariflari, interneti, xizmatlari va mobil aloqasi haqidagi ommabop savollarga javoblar.',
                ],
                'keywords' => [
                    'ru' => 'FAQ Perfectum, вопросы, интернет, тарифы',
                    'uz' => 'Perfectum FAQ, savollar, internet, tariflar',
                ],
            ],

            'contacts' => [
                'title' => [
                    'ru' => 'Контакты Perfectum — служба поддержки и офисы',
                    'uz' => 'Perfectum kontaktlari — qoʻllab-quvvatlash xizmati va ofislar',
                ],
                'description' => [
                    'ru' => 'Контакты Perfectum: телефоны поддержки, офисы обслуживания и информация для абонентов.',
                    'uz' => 'Perfectum kontaktlari: qoʻllab-quvvatlash telefonlari, xizmat koʻrsatish ofislari va abonentlar uchun maʼlumot.',
                ],
                'keywords' => [
                    'ru' => 'контакты Perfectum, поддержка, офисы',
                    'uz' => 'Perfectum kontaktlari, qoʻllab-quvvatlash, ofislar',
                ],
            ],

            'devices' => [
                'title' => [
                    'ru' => 'Каталог устройств Perfectum — смартфоны и роутеры с 5G',
                    'uz' => 'Perfectum qurilmalar katalogi — 5G smartfonlar va routerlar',
                ],
                'description' => [
                    'ru' => 'Выберите устройство для сети Perfectum: смартфоны, роутеры и модемы с поддержкой 5G.',
                    'uz' => 'Perfectum tarmogʻi uchun qurilma tanlang: 5G’ni qoʻllovchi smartfonlar, routerlar va modemlar.',
                ],
                'keywords' => [
                    'ru' => 'устройства Perfectum, 5G смартфоны, роутеры, модемы',
                    'uz' => 'Perfectum qurilmalari, 5G smartfonlar, routerlar, modemlar',
                ],
            ],

            'offices' => [
                'title' => [
                    'ru' => 'Офисы и дилеры Perfectum — адреса на карте',
                    'uz' => 'Perfectum ofislari va dilerlari — xaritadagi manzillar',
                ],
                'description' => [
                    'ru' => 'Найдите ближайший офис продаж или дилера Perfectum: адреса, режим работы и маршруты на карте.',
                    'uz' => 'Eng yaqin Perfectum savdo ofisi yoki dilerini toping: manzillar, ish vaqti va xaritadagi marshrutlar.',
                ],
                'keywords' => [
                    'ru' => 'офисы Perfectum, дилеры, адреса, карта',
                    'uz' => 'Perfectum ofislari, dilerlar, manzillar, xarita',
                ],
            ],

            'documents' => [
                'title' => [
                    'ru' => 'Документы Perfectum — договоры, оферты и политики',
                    'uz' => 'Perfectum hujjatlari — shartnomalar, ofertalar va siyosatlar',
                ],
                'description' => [
                    'ru' => 'Официальные документы Perfectum: публичные оферты, договоры, политики и формы для абонентов.',
                    'uz' => 'Perfectum rasmiy hujjatlari: ommaviy ofertalar, shartnomalar, siyosatlar va abonentlar uchun shakllar.',
                ],
                'keywords' => [
                    'ru' => 'документы Perfectum, оферта, договор, политика',
                    'uz' => 'Perfectum hujjatlari, oferta, shartnoma, siyosat',
                ],
            ],

            'help' => [
                'title' => [
                    'ru' => 'Поддержка Perfectum — помощь абонентам',
                    'uz' => 'Perfectum qoʻllab-quvvatlash — abonentlarga yordam',
                ],
                'description' => [
                    'ru' => 'Центр поддержки Perfectum: ответы на вопросы, свободные номера и связь с оператором.',
                    'uz' => 'Perfectum qoʻllab-quvvatlash markazi: savollarga javoblar, boʻsh raqamlar va operator bilan aloqa.',
                ],
                'keywords' => [
                    'ru' => 'поддержка Perfectum, помощь, свободные номера, связь',
                    'uz' => 'Perfectum qoʻllab-quvvatlash, yordam, boʻsh raqamlar, aloqa',
                ],
            ],

            'about_company' => [
                'title' => [
                    'ru' => 'О компании Perfectum — первая сеть 5G Standalone в Узбекистане',
                    'uz' => 'Perfectum kompaniyasi haqida — Oʻzbekistondagi birinchi 5G Standalone tarmogʻi',
                ],
                'description' => [
                    'ru' => 'Perfectum — телеком-оператор, запустивший первую сеть 5G Standalone в Центральной Азии. История, миссия и команда.',
                    'uz' => 'Perfectum — Markaziy Osiyoda birinchi 5G Standalone tarmogʻini ishga tushirgan telekom-operator. Tarix, missiya va jamoa.',
                ],
                'keywords' => [
                    'ru' => 'о компании, Perfectum, 5G Standalone, оператор',
                    'uz' => 'kompaniya haqida, Perfectum, 5G Standalone, operator',
                ],
            ],

            'careers' => [
                'title' => [
                    'ru' => 'Карьера в Perfectum — вакансии компании',
                    'uz' => 'Perfectum’da karyera — kompaniya vakansiyalari',
                ],
                'description' => [
                    'ru' => 'Работа в Perfectum: открытые вакансии, условия и развитие в команде телеком-оператора.',
                    'uz' => 'Perfectum’da ish: ochiq vakansiyalar, shartlar va telekom-operator jamoasida rivojlanish.',
                ],
                'keywords' => [
                    'ru' => 'карьера Perfectum, вакансии, работа, телеком',
                    'uz' => 'Perfectum karyera, vakansiyalar, ish, telekom',
                ],
            ],

            'procurement' => [
                'title' => [
                    'ru' => 'Закупки Perfectum — тендеры и конкурсы',
                    'uz' => 'Perfectum xaridlari — tenderlar va tanlovlar',
                ],
                'description' => [
                    'ru' => 'Актуальные тендеры и закупки Perfectum: условия участия, сроки и документация.',
                    'uz' => 'Perfectum’ning dolzarb tenderlari va xaridlari: ishtirok shartlari, muddatlar va hujjatlar.',
                ],
                'keywords' => [
                    'ru' => 'закупки Perfectum, тендеры, конкурсы',
                    'uz' => 'Perfectum xaridlari, tenderlar, tanlovlar',
                ],
            ],

            'cdma_connect' => [
                'title' => [
                    'ru' => 'Как подключиться к CDMA Perfectum',
                    'uz' => 'Perfectum CDMA’ga qanday ulanish mumkin',
                ],
                'description' => [
                    'ru' => 'Подключение домашнего интернета и связи CDMA Perfectum: шаги, условия и заявка онлайн.',
                    'uz' => 'Perfectum CDMA uy interneti va aloqasini ulash: qadamlar, shartlar va onlayn ariza.',
                ],
                'keywords' => [
                    'ru' => 'CDMA Perfectum, подключение, домашний интернет',
                    'uz' => 'CDMA Perfectum, ulanish, uy interneti',
                ],
            ],
        ];
    }
}
