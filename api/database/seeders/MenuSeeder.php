<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\MenuLocation;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->tree() as $location => $items) {
            $this->createItems(MenuLocation::from($location), $items);
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    private function createItems(MenuLocation $location, array $items, ?int $parentId = null): void
    {
        foreach ($items as $sort => $item) {
            $menu = Menu::updateOrCreate(
                [
                    'location' => $location,
                    'parent_id' => $parentId,
                    'key' => $item['key'],
                ],
                [
                    'name' => $item['name'],
                    'url' => $item['url'] ?? null,
                    'sort' => $sort + 1,
                    'status' => true,
                ]
            );

            if (isset($item['children'])) {
                $this->createItems($location, $item['children'], $menu->getKey());
            }
        }
    }

    /**
     * @return array<string, array<int, array<string, mixed>>>
     */
    private function tree(): array
    {
        return [
            MenuLocation::Header->value => [
                [
                    'key' => 'mobile',
                    'name' => ['ru' => 'Мобильная связь', 'uz' => 'Mobil aloqa'],
                    'children' => [
                        ['key' => 'tariffs', 'name' => ['ru' => 'Тарифы', 'uz' => 'Tariflar'], 'url' => $this->url('/tariffs')],
                        ['key' => 'numbers', 'name' => ['ru' => 'Свободные номера', 'uz' => 'Boʻsh raqamlar'], 'url' => $this->url('/numbers')],
                        ['key' => 'services', 'name' => ['ru' => 'Услуги', 'uz' => 'Xizmatlar'], 'url' => $this->url('/services')],
                        ['key' => 'devices', 'name' => ['ru' => 'Каталог устройств', 'uz' => 'Qurilmalar katalogi'], 'url' => $this->url('/devices')],
                        ['key' => 'actions', 'name' => ['ru' => 'Акции', 'uz' => 'Aksiyalar'], 'url' => $this->url('/actions')],
                    ],
                ],
                ['key' => 'internet', 'name' => ['ru' => 'Интернет', 'uz' => 'Internet']],
                ['key' => 'home-office', 'name' => ['ru' => 'Для Дома и офиса', 'uz' => 'Uy va ofis uchun']],
                ['key' => 'online', 'name' => ['ru' => 'Онлайн-сервисы', 'uz' => 'Onlayn xizmatlar']],
                [
                    'key' => 'support',
                    'name' => ['ru' => 'Поддержка', 'uz' => 'Qoʻllab-quvvatlash'],
                    'children' => [
                        ['key' => 'help', 'name' => ['ru' => 'Помощь', 'uz' => 'Yordam'], 'url' => $this->url('/help')],
                        ['key' => 'faq', 'name' => ['ru' => 'Вопросы и ответы', 'uz' => 'Savol-javob'], 'url' => $this->url('/faq')],
                        ['key' => 'coverage', 'name' => ['ru' => 'Карта покрытия', 'uz' => 'Qamrov xaritasi'], 'url' => $this->url('/coverage-area')],
                        ['key' => 'offices', 'name' => ['ru' => 'Офисы продаж', 'uz' => 'Sotuv ofislari'], 'url' => $this->url('/offices')],
                        ['key' => 'documents', 'name' => ['ru' => 'Документы', 'uz' => 'Hujjatlar'], 'url' => $this->url('/documents')],
                        ['key' => 'contacts', 'name' => ['ru' => 'Контакты', 'uz' => 'Kontaktlar'], 'url' => $this->url('/contacts')],
                    ],
                ],
                ['key' => 'news', 'name' => ['ru' => 'Новости', 'uz' => 'Yangiliklar'], 'url' => $this->url('/news')],
            ],

            MenuLocation::Footer->value => [
                [
                    'key' => 'home-internet',
                    'name' => ['ru' => 'Домашний интернет', 'uz' => 'Uy interneti'],
                    'column' => 1,
                    'children' => [
                        ['key' => 'coverage', 'name' => ['ru' => 'Карта покрытия', 'uz' => 'Qamrov xaritasi'], 'url' => $this->url('/coverage-area')],
                        ['key' => 'offices', 'name' => ['ru' => 'Офисы продаж', 'uz' => 'Sotuv ofislari'], 'url' => $this->url('/offices')],
                    ],
                ],
                [
                    'key' => 'mobile',
                    'name' => ['ru' => 'Мобильная связь', 'uz' => 'Mobil aloqa'],
                    'column' => 2,
                    'children' => [
                        ['key' => 'tariffs', 'name' => ['ru' => 'Тарифы', 'uz' => 'Tariflar'], 'url' => $this->url('/tariffs')],
                        ['key' => 'services', 'name' => ['ru' => 'Услуги', 'uz' => 'Xizmatlar'], 'url' => $this->url('/services')],
                        ['key' => 'numbers', 'name' => ['ru' => 'Свободные номера', 'uz' => 'Boʻsh raqamlar'], 'url' => $this->url('/numbers')],
                        ['key' => 'devices', 'name' => ['ru' => 'Устройства', 'uz' => 'Qurilmalar'], 'url' => $this->url('/devices')],
                        ['key' => 'coverage', 'name' => ['ru' => 'Зона покрытия', 'uz' => 'Qamrov hududi'], 'url' => $this->url('/coverage-area')],
                    ],
                ],
                [
                    'key' => 'useful',
                    'name' => ['ru' => 'Полезное', 'uz' => 'Foydali'],
                    'column' => 3,
                    'children' => [
                        ['key' => 'actions', 'name' => ['ru' => 'Акции', 'uz' => 'Aksiyalar'], 'url' => $this->url('/actions')],
                        ['key' => 'news', 'name' => ['ru' => 'Новости', 'uz' => 'Yangiliklar'], 'url' => $this->url('/news')],
                        ['key' => 'offices', 'name' => ['ru' => 'Офисы', 'uz' => 'Ofislar'], 'url' => $this->url('/offices')],
                        ['key' => 'dealers', 'name' => ['ru' => 'Дилеры', 'uz' => 'Dilerlar'], 'url' => $this->url('/cdma/dealers')],
                        ['key' => 'faq', 'name' => ['ru' => 'Полезно знать', 'uz' => 'Bilish foydali'], 'url' => $this->url('/faq')],
                    ],
                ],
                [
                    'key' => 'information',
                    'name' => ['ru' => 'Информация', 'uz' => 'Maʼlumot'],
                    'column' => 1,
                    'children' => [
                        ['key' => 'procurement', 'name' => ['ru' => 'Закупки', 'uz' => 'Xaridlar'], 'url' => $this->url('/procurement')],
                        ['key' => 'careers', 'name' => ['ru' => 'Карьера', 'uz' => 'Karyera'], 'url' => $this->url('/careers')],
                        ['key' => 'contacts', 'name' => ['ru' => 'Контакты', 'uz' => 'Kontaktlar'], 'url' => $this->url('/contacts')],
                        ['key' => 'documents', 'name' => ['ru' => 'Юридические документы', 'uz' => 'Yuridik hujjatlar'], 'url' => $this->url('/documents')],
                    ],
                ],
            ],

        ];
    }

    /**
     * @return array<string, string>
     */
    private function url(string $path): array
    {
        return collect(config('app.locales', [config('app.locale')]))
            ->mapWithKeys(fn (string $locale): array => [$locale => $path])
            ->all();
    }
}
