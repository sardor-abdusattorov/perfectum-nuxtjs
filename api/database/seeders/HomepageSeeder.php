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
                        'lead' => [
                            'ru' => '<p>Запуск самостоятельной сети 5G Standalone в Узбекистане. Скорость до 1 Гбит/с.</p>',
                            'uz' => '<p>O&#039;zbekistonda 5G Standalone mustaqil tarmoq ishga tushirildi. 1 Gbit/s gacha tezlik.</p>',
                        ],
                        'image' => null,
                        'title' => [
                            'ru' => '<p>Скорость <span data-color="accent" class="color" style="--color: accent; --dark-color: accent">меняющая</span> <span data-color="outline" class="color" style="--color: outline; --dark-color: outline">правила</span> игры</p>',
                            'uz' => '<p>Oʻyin <span data-color="outline" class="color" style="--color: outline; --dark-color: outline">qoidalarini</span> <span data-color="accent" class="color" style="--color: accent; --dark-color: accent">oʻzgartiradigan</span> tezlik</p>',
                        ],
                        'status' => true,
                        'buttons' => [
                            [
                                'url' => '/pages/kak-mozno-podklyucitsya',
                                'label' => [
                                    'ru' => 'Подключиться',
                                    'uz' => 'Ulanish',
                                ],
                                'style' => 'primary',
                                'status' => true,
                            ],
                            [
                                'url' => '/pages/kak-mozno-podklyucitsya',
                                'label' => [
                                    'ru' => 'Проверьте своё устройство',
                                    'uz' => 'Qurilmangizni tekshiring',
                                ],
                                'style' => 'secondary',
                                'status' => true,
                            ],
                        ],
                        'show_aside' => true,
                        'show_gauge' => true,
                        'description' => [
                            'ru' => 'Первая 5G SA сеть в центральной Азии',
                            'uz' => 'Markaziy Osiyodagi birinchi 5G SA tarmogʻi',
                        ],
                        'gauge_value' => 1000,
                    ],
                ],
            ],
            'marquee' => [
                'items' => [
                    [
                        'text' => [
                            'ru' => 'STANDALONE',
                            'uz' => 'STANDALONE',
                        ],
                        'image' => null,
                        'status' => true,
                    ],
                    [
                        'text' => [
                            'ru' => 'VONR',
                            'uz' => 'VONR',
                        ],
                        'image' => null,
                        'status' => true,
                    ],
                    [
                        'text' => [
                            'ru' => null,
                            'uz' => null,
                        ],
                        'image' => 'uploads/content-blocks/2026/08/01M0VJH1VT2X7QQC2XDJE549PJ.png',
                        'status' => true,
                    ],
                    [
                        'text' => [
                            'ru' => 'БЕСПРОВОДНОЙ ИНТЕРНЕТ',
                            'uz' => 'SIMSIZ INTERNET',
                        ],
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
                'link' => [
                    'url' => '/services',
                    'label' => [
                        'ru' => 'Все возможности →',
                        'uz' => 'Barcha imkoniyatlar →',
                    ],
                ],
                'cards' => [
                    [
                        'tag' => [
                            'ru' => null,
                            'uz' => null,
                        ],
                        'url' => '/pages/preimushhestva-5g-standalone',
                        'text' => [
                            'ru' => '<p>Первая 5G SA сеть<br>в Центральной Азии</p>',
                            'uz' => '<p>Markaziy Osiyodagi<br>birinchi 5G SA tarmog&#039;i</p>',
                        ],
                        'style' => 'lag',
                        'title' => [
                            'ru' => '<p>Преимущества<br>5G Standalone</p>',
                            'uz' => '<p>5G Standalone<br>afzalliklari</p>',
                        ],
                        'status' => true,
                        'link_label' => [
                            'ru' => 'Подробнее →',
                            'uz' => 'Batafsil →',
                        ],
                    ],
                    [
                        'tag' => [
                            'ru' => null,
                            'uz' => null,
                        ],
                        'url' => '/pages/sotni-ustroistv',
                        'text' => [
                            'ru' => '<p>Один роутер тянет всё: камеры, лампы, датчики, ТВ.</p>',
                            'uz' => '<p>Bitta router hammasini tortadi: kameralar, lampalar, datchiklar, TV.</p>',
                        ],
                        'style' => 'smart',
                        'title' => [
                            'ru' => '<p>Сотни устройств</p>',
                            'uz' => '<p>Yuzlab qurilmalar</p>',
                        ],
                        'status' => true,
                        'link_label' => [
                            'ru' => 'Подробнее →',
                            'uz' => 'Batafsil →',
                        ],
                    ],
                    [
                        'tag' => [
                            'ru' => null,
                            'uz' => null,
                        ],
                        'url' => '/pages/golosovaia-sviaz-vonr',
                        'text' => [
                            'ru' => '<p>Голос, как будто собеседник<br>находится рядом</p>',
                            'uz' => '<p>Suhbatdosh yoningizda turgandek<br>tiniq ovoz</p>',
                        ],
                        'style' => 'wire',
                        'title' => [
                            'ru' => '<p>Голосовая связь<br>VoNR</p>',
                            'uz' => '<p>VoNR ovozli<br>aloqa</p>',
                        ],
                        'status' => true,
                        'link_label' => [
                            'ru' => 'Подробнее →',
                            'uz' => 'Batafsil →',
                        ],
                    ],
                    [
                        'tag' => [
                            'ru' => null,
                            'uz' => null,
                        ],
                        'url' => '/pages/internet-bez-provodov',
                        'text' => [
                            'ru' => '<p>Скорости проводного интернета — без сверления стен<br>и ожидания монтажа.</p>',
                            'uz' => '<p>Simli internet tezligi — devorlarni teshmasdan<br>va montajni kutmasdan.</p>',
                        ],
                        'style' => 'pro',
                        'title' => [
                            'ru' => '<p>Интернет без проводов</p>',
                            'uz' => '<p>Simsiz internet</p>',
                        ],
                        'status' => true,
                        'link_label' => [
                            'ru' => 'Подробнее →',
                            'uz' => 'Batafsil →',
                        ],
                    ],
                ],
                'dials' => [
                    [
                        'to' => 1000,
                        'max' => 1000,
                        'from' => 980,
                        'color' => 'red',
                        'label' => '5G',
                    ],
                    [
                        'to' => 100,
                        'max' => 1000,
                        'from' => 90,
                        'color' => 'orange',
                        'label' => 'LTE',
                    ],
                ],
                'title' => [
                    'ru' => '<p>Что меняет <span data-color="accent" class="color" style="--color: accent; --dark-color: accent">5G SA</span></p>',
                    'uz' => '<p>Nimani oʻzgartiradi <span data-color="accent" class="color" style="--color: accent; --dark-color: accent">5G SA</span></p>',
                ],
                'speed_text' => [
                    'ru' => '<p>Скорость<br>нового уровня</p>',
                    'uz' => '<p>Yangi darajadagi<br>tezlik</p>',
                ],
                'speed_unit' => [
                    'ru' => 'Мбит/с',
                    'uz' => 'Mbit/s',
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
