<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Models\ContentBlock;
use Illuminate\Database\Seeder;

class AboutCompanySeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->blocks() as $key => $data) {
            ContentBlock::write(PageKey::AboutCompany, ContentBlockKey::from($key), $data);
        }
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function blocks(): array
    {
        return [
            'page_hero' => [
                'eyebrow' => ['ru' => 'Компания', 'uz' => 'Kompaniya'],
                'title' => [
                    'ru' => 'О <span class="color" data-color="accent">компании</span>',
                    'uz' => 'Kompaniya <span class="color" data-color="accent">haqida</span>',
                ],
                'subtitle' => [
                    'ru' => 'Perfectum™ — первая сеть 5G Standalone в Центральной Азии.',
                    'uz' => 'Perfectum™ — Markaziy Osiyodagi birinchi 5G Standalone tarmogʻi.',
                ],
            ],

            'stats' => [
                'items' => [
                    [
                        'value' => '1995',
                        'label' => ['ru' => 'Год основания', 'uz' => 'Tashkil etilgan yil'],
                    ],
                    [
                        'value' => '1-й',
                        'label' => ['ru' => '5G SA в центр. Азии', 'uz' => 'Markaziy Osiyoda 5G SA'],
                    ],
                    [
                        'value' => '1 Гбит/с',
                        'label' => ['ru' => 'Скорость 5G SA', 'uz' => '5G SA tezligi'],
                    ],
                    [
                        'value' => '13',
                        'label' => ['ru' => 'Регионов присутствия', 'uz' => 'Hududlar soni'],
                    ],
                ],
            ],

            'intro' => [
                'content' => [
                    'ru' => '<p>Perfectum™ — динамично развивающаяся телекоммуникационная компания с богатой историей и смелым взглядом в будущее. Основанная в 1995 году, сегодня мы внедряем передовые технологии мобильной и фиксированной связи и формируем новый уровень телекоммуникационного рынка Узбекистана.</p><p>В 2023 году Perfectum™ запустила масштабный проект «с нуля» по созданию национальной сети 5G Standalone — первой полностью самостоятельной сети пятого поколения в Центральной Азии, без привязки к существующей 4G-инфраструктуре.</p>',
                    'uz' => '<p>Perfectum™ — boy tarixga va kelajakka dadil qarashga ega jadal rivojlanayotgan telekommunikatsiya kompaniyasi. 1995-yilda tashkil etilgan boʻlib, bugun biz mobil va statsionar aloqaning ilgʻor texnologiyalarini joriy etib, Oʻzbekiston telekommunikatsiya bozorining yangi darajasini shakllantirmoqdamiz.</p><p>2023-yilda Perfectum™ nolddan milliy 5G Standalone tarmogʻini yaratish boʻyicha yirik loyihani ishga tushirdi — bu Markaziy Osiyodagi birinchi toʻliq mustaqil beshinchi avlod tarmogʻi boʻlib, mavjud 4G infratuzilmasiga bogʻliq emas.</p>',
                ],
            ],

            'timeline' => [
                'items' => [
                    [
                        'year' => ['ru' => '1995', 'uz' => '1995'],
                        'text' => [
                            'ru' => 'Основание компании, начало работы на телекоммуникационном рынке Узбекистана.',
                            'uz' => 'Kompaniyaga asos solindi, Oʻzbekiston telekommunikatsiya bozorida faoliyat boshlandi.',
                        ],
                    ],
                    [
                        'year' => ['ru' => '2023', 'uz' => '2023'],
                        'text' => [
                            'ru' => 'Запуск проекта по строительству национальной сети 5G Standalone «с нуля».',
                            'uz' => 'Milliy 5G Standalone tarmogʻini nolddan qurish loyihasi ishga tushirildi.',
                        ],
                    ],
                    [
                        'year' => ['ru' => '2024–2025', 'uz' => '2024–2025'],
                        'text' => [
                            'ru' => 'Расширение зоны покрытия 5G SA на ключевые города и регионы страны.',
                            'uz' => '5G SA qamrov hududi mamlakatning asosiy shahar va hududlariga kengaytirildi.',
                        ],
                    ],
                    [
                        'year' => ['ru' => 'Сегодня', 'uz' => 'Bugun'],
                        'text' => [
                            'ru' => 'Perfectum — современный оператор полного цикла: мобильная связь, домашний интернет, CDMA.',
                            'uz' => 'Perfectum — toʻliq siklli zamonaviy operator: mobil aloqa, uy interneti, CDMA.',
                        ],
                    ],
                ],
            ],
        ];
    }
}
