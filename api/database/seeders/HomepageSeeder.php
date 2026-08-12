<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Models\ContentBlock;
use Illuminate\Database\Seeder;

class HomepageSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->blocks() as $key => $data) {
            ContentBlock::write(PageKey::Home, ContentBlockKey::from($key), $data);
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
                        'description' => ['ru' => 'Первая 5G SA сеть в центральной Азии', 'uz' => 'Markaziy Osiyodagi birinchi 5G SA tarmogʻi'],
                        'title' => [
                            'ru' => 'Скорость которая <span class="color" data-color="accent">меняет</span> <span class="color" data-color="outline">правила</span> игры',
                            'uz' => 'Oʻyin <span class="color" data-color="outline">qoidalarini</span> <span class="color" data-color="accent">oʻzgartiradigan</span> tezlik',
                        ],
                        'lead' => [
                            'ru' => 'Запуск самостоятельной сети <span>5G Standalone</span> в Узбекистане Скорости до <span>1 Гбит/с</span>, минимальные задержки, новые сценарии для дома и бизнеса',
                            'uz' => 'Oʻzbekistonda mustaqil <span>5G Standalone</span> tarmogʻining ishga tushirilishi. Tezlik <span>1 Gbit/s</span> gacha, minimal kechikishlar, uy va biznes uchun yangi stsenariylar',
                        ],
                        'image' => null,
                        'buttons' => [
                            [
                                'label' => ['ru' => 'Подключиться', 'uz' => 'Ulanish'],
                                'url' => '/tariffs',
                                'style' => 'primary',
                                'status' => true,
                            ],
                            [
                                'label' => ['ru' => 'Проверить покрытие', 'uz' => 'Qamrovni tekshirish'],
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
                        'text' => ['ru' => 'STANDALONE', 'uz' => 'STANDALONE'],
                        'image' => null,
                        'status' => true,
                    ],
                    [
                        'text' => ['ru' => 'VONR', 'uz' => 'VONR'],
                        'image' => null,
                        'status' => true,
                    ],
                    [
                        'text' => ['ru' => 'PERFECTUM', 'uz' => 'PERFECTUM'],
                        'image' => null,
                        'status' => true,
                    ],
                    [
                        'text' => ['ru' => 'БЕСПРОВОДНОЙ ИНТЕРНЕТ', 'uz' => 'SIMSIZ INTERNET'],
                        'image' => null,
                        'status' => true,
                    ],
                ],
            ],

            'choose' => [
                'title' => ['ru' => 'Выбери своё', 'uz' => 'Oʻzingnikini tanla'],
                'link' => [
                    'label' => ['ru' => 'Все услуги', 'uz' => 'Barcha xizmatlar'],
                    'url' => '/services',
                ],
                'cards' => [
                    [
                        'category' => ['ru' => 'Мобильная связь', 'uz' => 'Mobil aloqa'],
                        'name' => [
                            'ru' => '<p>Тариф<br>Perfectum 5G</p>',
                            'uz' => '<p>Perfectum 5G<br>tarifi</p>',
                        ],
                        'url' => '/tariffs',
                        'color' => 'red',
                        'status' => true,
                    ],
                    [
                        'category' => ['ru' => 'Домой', 'uz' => 'Uyga'],
                        'name' => [
                            'ru' => '<p>Беспроводной<br>интернет</p>',
                            'uz' => '<p>Simsiz<br>internet</p>',
                        ],
                        'url' => '/tariffs',
                        'color' => 'scarlet',
                        'status' => true,
                    ],
                    [
                        'category' => ['ru' => 'Бизнес', 'uz' => 'Biznes'],
                        'name' => [
                            'ru' => '<p>5G для<br>компаний</p>',
                            'uz' => '<p>Kompaniyalar<br>uchun 5G</p>',
                        ],
                        'url' => '/tariffs',
                        'color' => 'dark',
                        'status' => true,
                    ],
                    [
                        'category' => ['ru' => 'Устройства', 'uz' => 'Qurilmalar'],
                        'name' => [
                            'ru' => '<p>Роутеры<br>и модемы</p>',
                            'uz' => '<p>Routerlar<br>va modemlar</p>',
                        ],
                        'url' => '/devices',
                        'color' => 'ruby',
                        'status' => true,
                    ],
                ],
            ],

            'tariffs' => [
                'eyebrow' => ['ru' => 'Собери свой тариф', 'uz' => 'Oʻz tarifingizni yigʻing'],
                'title' => [
                    'ru' => '<p>Только то,<br>что нужно тебе</p>',
                    'uz' => '<p>Faqat sizga<br>kerak boʻlgani</p>',
                ],
            ],

            'features' => [
                'title' => [
                    'ru' => '<p>Что меняет <span class="color" data-color="accent">5G SA</span></p>',
                    'uz' => '<p>Nimani oʻzgartiradi <span class="color" data-color="accent">5G SA</span></p>',
                ],
                'link' => [
                    'label' => ['ru' => 'Все возможности', 'uz' => 'Barcha imkoniyatlar'],
                    'url' => '/services',
                ],
                'cards' => [
                    [
                        'tag' => ['ru' => 'Гейминг Стриминг', 'uz' => 'Geyming va striming'],
                        'title' => [
                            'ru' => '<p>Лаг как<br>понятие<br>исчезает</p>',
                            'uz' => '<p>Lag degan<br>tushuncha<br>yoʻqoladi</p>',
                        ],
                        'text' => [
                            'ru' => '<p>Задержка 5 мс делает облачный гейминг<br>неотличимым от локального. Стримы в 4K<br>без буфера — даже в час пик.</p>',
                            'uz' => '<p>5 ms kechikish bulutli geymingni lokal<br>oʻyindan farqlanmas qiladi. 4K oqimlar<br>bufersiz — hatto eng gavjum soatlarda ham.</p>',
                        ],
                        'link_label' => ['ru' => 'Подробнее', 'uz' => 'Batafsil'],
                        'url' => '/services',
                        'style' => 'lag',
                        'status' => true,
                    ],
                    [
                        'tag' => ['ru' => 'умный дом', 'uz' => 'aqlli uy'],
                        'title' => ['ru' => 'Сотни устройств', 'uz' => 'Yuzlab qurilmalar'],
                        'text' => [
                            'ru' => 'Один роутер тянет всё: камеры, лампы, датчики, ТВ.',
                            'uz' => 'Bitta router hammasiga yetadi: kameralar, chiroqlar, datchiklar, TV.',
                        ],
                        'link_label' => ['ru' => 'Подробнее', 'uz' => 'Batafsil'],
                        'url' => '/devices',
                        'style' => 'smart',
                        'status' => true,
                    ],
                    [
                        'tag' => ['ru' => 'гейминг', 'uz' => 'geyming'],
                        'title' => [
                            'ru' => '<p>Интернет<br>без проводов</p>',
                            'uz' => '<p>Simsiz<br>internet</p>',
                        ],
                        'text' => [
                            'ru' => '<p>Скорости оптоволокна — без копания<br>траншей и ожидания монтажа.</p>',
                            'uz' => '<p>Optik tolali tezliklar — xandaq qazishsiz<br>va oʻrnatishni kutishsiz.</p>',
                        ],
                        'link_label' => ['ru' => 'Заказать', 'uz' => 'Buyurtma berish'],
                        'url' => '/tariffs',
                        'style' => 'wire',
                        'status' => true,
                    ],
                    [
                        'title' => [
                            'ru' => '<p>Преимущества<br>5G Standalone</p>',
                            'uz' => '<p>5G Standalone<br>afzalliklari</p>',
                        ],
                        'text' => [
                            'ru' => '<p>Скорости оптоволокна — без копания<br>траншей и ожидания монтажа.</p>',
                            'uz' => '<p>Optik tolali tezliklar — xandaq qazishsiz<br>va oʻrnatishni kutishsiz.</p>',
                        ],
                        'link_label' => ['ru' => 'Корпоративный отдел', 'uz' => 'Korporativ boʻlim'],
                        'url' => '/contacts',
                        'style' => 'pro',
                        'status' => true,
                    ],
                ],
                'speed_text' => [
                    'ru' => '<p>Скорость<br>нового уровня</p>',
                    'uz' => '<p>Yangi darajadagi<br>tezlik</p>',
                ],
                'speed_unit' => ['ru' => 'Мбит/с', 'uz' => 'Mbit/s'],
                'dials' => [
                    [
                        'label' => '5G',
                        'color' => 'red',
                        'max' => 1000,
                        'from' => 980,
                        'to' => 1000,
                    ],
                    [
                        'label' => 'LTE',
                        'color' => 'orange',
                        'max' => 1000,
                        'from' => 90,
                        'to' => 100,
                    ],
                ],
            ],

            'coverage' => [
                'title' => ['ru' => 'Где работает', 'uz' => 'Qayerda ishlaydi'],
                'subtitle' => [
                    'ru' => '5G SA уже развёрнут в крупнейших городах. Подключение и расширение покрытия — каждый месяц.',
                    'uz' => '5G SA yirik shaharlarda allaqachon ishga tushirilgan. Ulanish va qamrovni kengaytirish — har oyda.',
                ],
                'cities' => [
                    [
                        'name' => ['ru' => 'Ташкент', 'uz' => 'Toshkent'],
                        'status_text' => ['ru' => 'Полное покрытие', 'uz' => 'Toʻliq qamrov'],
                        'active' => true,
                        'status' => true,
                    ],
                    [
                        'name' => ['ru' => 'Самарканд', 'uz' => 'Samarqand'],
                        'status_text' => ['ru' => 'Скоро', 'uz' => 'Tez orada'],
                        'active' => false,
                        'status' => true,
                    ],
                    [
                        'name' => ['ru' => 'Бухара', 'uz' => 'Buxoro'],
                        'status_text' => ['ru' => 'Скоро', 'uz' => 'Tez orada'],
                        'active' => false,
                        'status' => true,
                    ],
                    [
                        'name' => ['ru' => 'Наманган', 'uz' => 'Namangan'],
                        'status_text' => ['ru' => 'Скоро', 'uz' => 'Tez orada'],
                        'active' => false,
                        'status' => true,
                    ],
                    [
                        'name' => ['ru' => 'Андижан', 'uz' => 'Andijon'],
                        'status_text' => ['ru' => 'Скоро', 'uz' => 'Tez orada'],
                        'active' => false,
                        'status' => true,
                    ],
                ],
            ],

            'app_promo' => [
                'title' => [
                    'ru' => '<p>УПРАВЛЯЙ<br>В ОДИН ТАП</p>',
                    'uz' => '<p>BIR TEGINISHDA<br>BOSHQARING</p>',
                ],
                'description' => [
                    'ru' => '<p>Тариф, остатки, оплата, поддержка — всё<br>в приложении Perfectum. Узбекский,<br>русский, английский.</p>',
                    'uz' => '<p>Tarif, qoldiqlar, toʻlov, qoʻllab-quvvatlash — barchasi<br>Perfectum ilovasida. Oʻzbek,<br>rus, ingliz tillari.</p>',
                ],
                'watermark' => '5G',
            ],
        ];
    }
}
