<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Support\Content;
use Illuminate\Database\Seeder;

class HomepageSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->blocks() as $key => $data) {
            Content::save(PageKey::Home, ContentBlockKey::from($key), $data);
        }
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function blocks(): array
    {
        return [
            'hero' => [
                'slides' => [
                    [
                        'description' => ['ru' => 'Первая 5G SA сеть в центральной Азии', 'uz' => 'Markaziy Osiyodagi birinchi 5G SA tarmogʻi', 'en' => 'The first 5G SA network in Central Asia'],
                        'title' => [
                            'ru' => 'Скорость которая <span class="hero__title-red">меняет</span> <span class="hero__title-outline">правила</span> игры',
                            'uz' => 'Oʻyin <span class="hero__title-outline">qoidalarini</span> <span class="hero__title-red">oʻzgartiradigan</span> tezlik',
                            'en' => 'Speed that <span class="hero__title-red">changes</span> the <span class="hero__title-outline">rules</span> of the game',
                        ],
                        'lead' => [
                            'ru' => 'Запуск самостоятельной сети <span>5G Standalone</span> в Узбекистане Скорости до <span>1 Гбит/с</span>, минимальные задержки, новые сценарии для дома и бизнеса',
                            'uz' => 'Oʻzbekistonda mustaqil <span>5G Standalone</span> tarmogʻining ishga tushirilishi. Tezlik <span>1 Gbit/s</span> gacha, minimal kechikishlar, uy va biznes uchun yangi stsenariylar',
                            'en' => 'The launch of a <span>5G Standalone</span> network in Uzbekistan. Speeds up to <span>1 Gbps</span>, minimal latency, new scenarios for home and business',
                        ],
                        'image' => null,
                        'buttons' => [
                            [
                                'label' => ['ru' => 'Подключиться', 'uz' => 'Ulanish', 'en' => 'Connect'],
                                'url' => '/tariffs',
                                'style' => 'primary',
                                'status' => true,
                            ],
                            [
                                'label' => ['ru' => 'Проверить покрытие', 'uz' => 'Qamrovni tekshirish', 'en' => 'Check coverage'],
                                'url' => '/coverage-area',
                                'style' => 'secondary',
                                'status' => true,
                            ],
                        ],
                        'show_aside' => true,
                        'show_gauge' => true,
                        'gauge_value' => '1 000',
                        'status' => true,
                    ],
                ],
            ],

            'marquee' => [
                'items' => [
                    [
                        'text' => ['ru' => 'STANDALONE', 'uz' => 'STANDALONE', 'en' => 'STANDALONE'],
                        'image' => null,
                        'status' => true,
                    ],
                    [
                        'text' => ['ru' => 'VONR', 'uz' => 'VONR', 'en' => 'VONR'],
                        'image' => null,
                        'status' => true,
                    ],
                    [
                        'text' => ['ru' => 'PERFECTUM', 'uz' => 'PERFECTUM', 'en' => 'PERFECTUM'],
                        'image' => null,
                        'status' => true,
                    ],
                    [
                        'text' => ['ru' => 'БЕСПРОВОДНОЙ ИНТЕРНЕТ', 'uz' => 'SIMSIZ INTERNET', 'en' => 'WIRELESS INTERNET'],
                        'image' => null,
                        'status' => true,
                    ],
                ],
            ],

            'choose' => [
                'title' => ['ru' => 'Выбери своё', 'uz' => 'Oʻzingnikini tanla', 'en' => 'Choose your own'],
                'link' => [
                    'label' => ['ru' => 'Все услуги', 'uz' => 'Barcha xizmatlar', 'en' => 'All services'],
                    'url' => '/services',
                ],
                'cards' => [
                    [
                        'category' => ['ru' => 'Мобильная связь', 'uz' => 'Mobil aloqa', 'en' => 'Mobile'],
                        'name' => [
                            'ru' => "Тариф\nPerfectum 5G",
                            'uz' => "Perfectum 5G\ntarifi",
                            'en' => "Perfectum 5G\ntariff",
                        ],
                        'url' => '/tariffs',
                        'color' => 'red',
                        'status' => true,
                    ],
                    [
                        'category' => ['ru' => 'Домой', 'uz' => 'Uyga', 'en' => 'Home'],
                        'name' => [
                            'ru' => "Беспроводной\nинтернет",
                            'uz' => "Simsiz\ninternet",
                            'en' => "Wireless\ninternet",
                        ],
                        'url' => '/tariffs',
                        'color' => 'scarlet',
                        'status' => true,
                    ],
                    [
                        'category' => ['ru' => 'Бизнес', 'uz' => 'Biznes', 'en' => 'Business'],
                        'name' => [
                            'ru' => "5G для\nкомпаний",
                            'uz' => "Kompaniyalar\nuchun 5G",
                            'en' => "5G for\ncompanies",
                        ],
                        'url' => '/tariffs',
                        'color' => 'dark',
                        'status' => true,
                    ],
                    [
                        'category' => ['ru' => 'Устройства', 'uz' => 'Qurilmalar', 'en' => 'Devices'],
                        'name' => [
                            'ru' => "Роутеры\nи модемы",
                            'uz' => "Routerlar\nva modemlar",
                            'en' => "Routers\nand modems",
                        ],
                        'url' => '/devices',
                        'color' => 'ruby',
                        'status' => true,
                    ],
                ],
            ],

            'tariffs' => [
                'eyebrow' => ['ru' => 'Собери свой тариф', 'uz' => 'Oʻz tarifingizni yigʻing', 'en' => 'Build your own tariff'],
                'title' => [
                    'ru' => "Только то,\nчто нужно тебе",
                    'uz' => "Faqat sizga\nkerak boʻlgani",
                    'en' => "Only what\nyou need",
                ],
            ],

            'features' => [
                'title' => ['ru' => 'Что меняет', 'uz' => 'Nimani oʻzgartiradi', 'en' => 'What changes with'],
                'title_accent' => ['ru' => '5G SA', 'uz' => '5G SA', 'en' => '5G SA'],
                'link' => [
                    'label' => ['ru' => 'Все возможности', 'uz' => 'Barcha imkoniyatlar', 'en' => 'All features'],
                    'url' => '/services',
                ],
                'cards' => [
                    [
                        'tag' => ['ru' => 'Гейминг Стриминг', 'uz' => 'Geyming va striming', 'en' => 'Gaming and streaming'],
                        'title' => [
                            'ru' => "Лаг как\nпонятие\nисчезает",
                            'uz' => "Lag degan\ntushuncha\nyoʻqoladi",
                            'en' => "Lag as a\nconcept\ndisappears",
                        ],
                        'text' => [
                            'ru' => "Задержка 5 мс делает облачный гейминг\nнеотличимым от локального. Стримы в 4K\nбез буфера — даже в час пик.",
                            'uz' => "5 ms kechikish bulutli geymingni lokal\noʻyindan farqlanmas qiladi. 4K oqimlar\nbufersiz — hatto eng gavjum soatlarda ham.",
                            'en' => "A 5 ms latency makes cloud gaming\nindistinguishable from local play. 4K streams\nwith no buffering — even at peak hours.",
                        ],
                        'link_label' => ['ru' => 'Подробнее', 'uz' => 'Batafsil', 'en' => 'Read more'],
                        'url' => '/services',
                        'style' => 'lag',
                        'status' => true,
                    ],
                    [
                        'tag' => ['ru' => 'умный дом', 'uz' => 'aqlli uy', 'en' => 'smart home'],
                        'title' => ['ru' => 'Сотни устройств', 'uz' => 'Yuzlab qurilmalar', 'en' => 'Hundreds of devices'],
                        'text' => [
                            'ru' => 'Один роутер тянет всё: камеры, лампы, датчики, ТВ.',
                            'uz' => 'Bitta router hammasiga yetadi: kameralar, chiroqlar, datchiklar, TV.',
                            'en' => 'One router handles it all: cameras, lights, sensors, TV.',
                        ],
                        'link_label' => ['ru' => 'Подробнее', 'uz' => 'Batafsil', 'en' => 'Read more'],
                        'url' => '/devices',
                        'style' => 'smart',
                        'status' => true,
                    ],
                    [
                        'tag' => ['ru' => 'гейминг', 'uz' => 'geyming', 'en' => 'gaming'],
                        'title' => [
                            'ru' => "Интернет\nбез проводов",
                            'uz' => "Simsiz\ninternet",
                            'en' => "Internet\nwithout wires",
                        ],
                        'text' => [
                            'ru' => "Скорости оптоволокна — без копания\nтраншей и ожидания монтажа.",
                            'uz' => "Optik tolali tezliklar — xandaq qazishsiz\nva oʻrnatishni kutishsiz.",
                            'en' => "Fibre-optic speeds — with no trenches to dig\nand no waiting for installation.",
                        ],
                        'link_label' => ['ru' => 'Заказать', 'uz' => 'Buyurtma berish', 'en' => 'Order now'],
                        'url' => '/tariffs',
                        'style' => 'wire',
                        'status' => true,
                    ],
                    [
                        'title' => [
                            'ru' => "Преимущества\n5G Standalone",
                            'uz' => "5G Standalone\nafzalliklari",
                            'en' => "Advantages of\n5G Standalone",
                        ],
                        'text' => [
                            'ru' => "Скорости оптоволокна — без копания\nтраншей и ожидания монтажа.",
                            'uz' => "Optik tolali tezliklar — xandaq qazishsiz\nva oʻrnatishni kutishsiz.",
                            'en' => "Fibre-optic speeds — with no trenches to dig\nand no waiting for installation.",
                        ],
                        'link_label' => ['ru' => 'Корпоративный отдел', 'uz' => 'Korporativ boʻlim', 'en' => 'Corporate department'],
                        'url' => '/contacts',
                        'style' => 'pro',
                        'status' => true,
                    ],
                ],
                'speed_text' => [
                    'ru' => "Скорость\nнового уровня",
                    'uz' => "Yangi darajadagi\ntezlik",
                    'en' => "A new level\nof speed",
                ],
                'speed_unit' => ['ru' => 'Мбит/с', 'uz' => 'Mbit/s', 'en' => 'Mbps'],
                'dials' => [
                    [
                        'label' => '5G',
                        'color' => 'red',
                        'max' => 1000,
                        'from' => 980,
                        'to' => 1000,
                        'status' => true,
                    ],
                    [
                        'label' => 'LTE',
                        'color' => 'orange',
                        'max' => 1000,
                        'from' => 90,
                        'to' => 100,
                        'status' => true,
                    ],
                ],
            ],

            'coverage' => [
                'title' => ['ru' => 'Где работает', 'uz' => 'Qayerda ishlaydi', 'en' => 'Where it works'],
                'subtitle' => [
                    'ru' => '5G SA уже развёрнут в крупнейших городах. Подключение и расширение покрытия — каждый месяц.',
                    'uz' => '5G SA yirik shaharlarda allaqachon ishga tushirilgan. Ulanish va qamrovni kengaytirish — har oyda.',
                    'en' => '5G SA is already deployed in the largest cities. Connections and coverage expansion — every month.',
                ],
                'cities' => [
                    [
                        'name' => ['ru' => 'Ташкент', 'uz' => 'Toshkent', 'en' => 'Tashkent'],
                        'status_text' => ['ru' => 'Полное покрытие', 'uz' => 'Toʻliq qamrov', 'en' => 'Full coverage'],
                        'active' => true,
                        'status' => true,
                    ],
                    [
                        'name' => ['ru' => 'Самарканд', 'uz' => 'Samarqand', 'en' => 'Samarkand'],
                        'status_text' => ['ru' => 'Скоро', 'uz' => 'Tez orada', 'en' => 'Coming soon'],
                        'active' => false,
                        'status' => true,
                    ],
                    [
                        'name' => ['ru' => 'Бухара', 'uz' => 'Buxoro', 'en' => 'Bukhara'],
                        'status_text' => ['ru' => 'Скоро', 'uz' => 'Tez orada', 'en' => 'Coming soon'],
                        'active' => false,
                        'status' => true,
                    ],
                    [
                        'name' => ['ru' => 'Наманган', 'uz' => 'Namangan', 'en' => 'Namangan'],
                        'status_text' => ['ru' => 'Скоро', 'uz' => 'Tez orada', 'en' => 'Coming soon'],
                        'active' => false,
                        'status' => true,
                    ],
                    [
                        'name' => ['ru' => 'Андижан', 'uz' => 'Andijon', 'en' => 'Andijan'],
                        'status_text' => ['ru' => 'Скоро', 'uz' => 'Tez orada', 'en' => 'Coming soon'],
                        'active' => false,
                        'status' => true,
                    ],
                ],
            ],

            'app_promo' => [
                'title' => [
                    'ru' => "УПРАВЛЯЙ\nВ ОДИН ТАП",
                    'uz' => "BIR TEGINISHDA\nBOSHQARING",
                    'en' => "MANAGE\nIN ONE TAP",
                ],
                'description' => [
                    'ru' => "Тариф, остатки, оплата, поддержка — всё\nв приложении Perfectum. Узбекский,\nрусский, английский.",
                    'uz' => "Tarif, qoldiqlar, toʻlov, qoʻllab-quvvatlash — barchasi\nPerfectum ilovasida. Oʻzbek,\nrus, ingliz tillari.",
                    'en' => "Tariff, balances, payment, support — everything\nin the Perfectum app. Uzbek,\nRussian, English.",
                ],
                'watermark' => '5G',
            ],
        ];
    }
}
