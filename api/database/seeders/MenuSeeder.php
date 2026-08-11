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
                    'column_position' => $item['column'] ?? null,
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
                    'name' => ['ru' => 'Мобильная связь', 'uz' => 'Mobil aloqa', 'en' => 'Mobile'],
                    'children' => [
                        ['key' => 'tariffs', 'name' => ['ru' => 'Тарифы', 'uz' => 'Tariflar', 'en' => 'Tariffs'], 'url' => $this->url('/tariffs')],
                        ['key' => 'numbers', 'name' => ['ru' => 'Свободные номера', 'uz' => 'Boʻsh raqamlar', 'en' => 'Free numbers'], 'url' => $this->url('/numbers')],
                        ['key' => 'services', 'name' => ['ru' => 'Услуги', 'uz' => 'Xizmatlar', 'en' => 'Services'], 'url' => $this->url('/services')],
                        ['key' => 'devices', 'name' => ['ru' => 'Каталог устройств', 'uz' => 'Qurilmalar katalogi', 'en' => 'Device catalogue'], 'url' => $this->url('/devices')],
                        ['key' => 'actions', 'name' => ['ru' => 'Акции', 'uz' => 'Aksiyalar', 'en' => 'Promotions'], 'url' => $this->url('/actions')],
                    ],
                ],
                ['key' => 'internet', 'name' => ['ru' => 'Интернет', 'uz' => 'Internet', 'en' => 'Internet']],
                ['key' => 'home-office', 'name' => ['ru' => 'Для Дома и офиса', 'uz' => 'Uy va ofis uchun', 'en' => 'Home and office']],
                ['key' => 'online', 'name' => ['ru' => 'Онлайн-сервисы', 'uz' => 'Onlayn xizmatlar', 'en' => 'Online services']],
                [
                    'key' => 'support',
                    'name' => ['ru' => 'Поддержка', 'uz' => 'Qoʻllab-quvvatlash', 'en' => 'Support'],
                    'children' => [
                        ['key' => 'help', 'name' => ['ru' => 'Помощь', 'uz' => 'Yordam', 'en' => 'Help'], 'url' => $this->url('/help')],
                        ['key' => 'faq', 'name' => ['ru' => 'Вопросы и ответы', 'uz' => 'Savol-javob', 'en' => 'FAQ'], 'url' => $this->url('/faq')],
                        ['key' => 'coverage', 'name' => ['ru' => 'Карта покрытия', 'uz' => 'Qamrov xaritasi', 'en' => 'Coverage map'], 'url' => $this->url('/coverage-area')],
                        ['key' => 'offices', 'name' => ['ru' => 'Офисы продаж', 'uz' => 'Sotuv ofislari', 'en' => 'Sales offices'], 'url' => $this->url('/offices')],
                        ['key' => 'documents', 'name' => ['ru' => 'Документы', 'uz' => 'Hujjatlar', 'en' => 'Documents'], 'url' => $this->url('/documents')],
                        ['key' => 'contacts', 'name' => ['ru' => 'Контакты', 'uz' => 'Kontaktlar', 'en' => 'Contacts'], 'url' => $this->url('/contacts')],
                    ],
                ],
                ['key' => 'news', 'name' => ['ru' => 'Новости', 'uz' => 'Yangiliklar', 'en' => 'News'], 'url' => $this->url('/news')],
            ],

            MenuLocation::Footer->value => [
                [
                    'key' => 'home-internet',
                    'name' => ['ru' => 'Домашний интернет', 'uz' => 'Uy interneti', 'en' => 'Home internet'],
                    'column' => 1,
                    'children' => [
                        ['key' => 'coverage', 'name' => ['ru' => 'Карта покрытия', 'uz' => 'Qamrov xaritasi', 'en' => 'Coverage map'], 'url' => $this->url('/coverage-area')],
                        ['key' => 'offices', 'name' => ['ru' => 'Офисы продаж', 'uz' => 'Sotuv ofislari', 'en' => 'Sales offices'], 'url' => $this->url('/offices')],
                    ],
                ],
                [
                    'key' => 'mobile',
                    'name' => ['ru' => 'Мобильная связь', 'uz' => 'Mobil aloqa', 'en' => 'Mobile'],
                    'column' => 2,
                    'children' => [
                        ['key' => 'tariffs', 'name' => ['ru' => 'Тарифы', 'uz' => 'Tariflar', 'en' => 'Tariffs'], 'url' => $this->url('/tariffs')],
                        ['key' => 'services', 'name' => ['ru' => 'Услуги', 'uz' => 'Xizmatlar', 'en' => 'Services'], 'url' => $this->url('/services')],
                        ['key' => 'numbers', 'name' => ['ru' => 'Свободные номера', 'uz' => 'Boʻsh raqamlar', 'en' => 'Free numbers'], 'url' => $this->url('/numbers')],
                        ['key' => 'devices', 'name' => ['ru' => 'Устройства', 'uz' => 'Qurilmalar', 'en' => 'Devices'], 'url' => $this->url('/devices')],
                        ['key' => 'coverage', 'name' => ['ru' => 'Зона покрытия', 'uz' => 'Qamrov hududi', 'en' => 'Coverage area'], 'url' => $this->url('/coverage-area')],
                    ],
                ],
                [
                    'key' => 'useful',
                    'name' => ['ru' => 'Полезное', 'uz' => 'Foydali', 'en' => 'Useful'],
                    'column' => 3,
                    'children' => [
                        ['key' => 'actions', 'name' => ['ru' => 'Акции', 'uz' => 'Aksiyalar', 'en' => 'Promotions'], 'url' => $this->url('/actions')],
                        ['key' => 'news', 'name' => ['ru' => 'Новости', 'uz' => 'Yangiliklar', 'en' => 'News'], 'url' => $this->url('/news')],
                        ['key' => 'offices', 'name' => ['ru' => 'Офисы', 'uz' => 'Ofislar', 'en' => 'Offices'], 'url' => $this->url('/offices')],
                        ['key' => 'dealers', 'name' => ['ru' => 'Дилеры', 'uz' => 'Dilerlar', 'en' => 'Dealers'], 'url' => $this->url('/cdma/dealers')],
                        ['key' => 'faq', 'name' => ['ru' => 'Полезно знать', 'uz' => 'Bilish foydali', 'en' => 'Good to know'], 'url' => $this->url('/faq')],
                    ],
                ],
                [
                    'key' => 'information',
                    'name' => ['ru' => 'Информация', 'uz' => 'Maʼlumot', 'en' => 'Information'],
                    'column' => 1,
                    'children' => [
                        ['key' => 'procurement', 'name' => ['ru' => 'Закупки', 'uz' => 'Xaridlar', 'en' => 'Procurement'], 'url' => $this->url('/procurement')],
                        ['key' => 'careers', 'name' => ['ru' => 'Карьера', 'uz' => 'Karyera', 'en' => 'Careers'], 'url' => $this->url('/careers')],
                        ['key' => 'contacts', 'name' => ['ru' => 'Контакты', 'uz' => 'Kontaktlar', 'en' => 'Contacts'], 'url' => $this->url('/contacts')],
                        ['key' => 'documents', 'name' => ['ru' => 'Юридические документы', 'uz' => 'Yuridik hujjatlar', 'en' => 'Legal documents'], 'url' => $this->url('/documents')],
                    ],
                ],
            ],

            MenuLocation::CdmaHeader->value => [
                ['key' => 'tariffs', 'name' => ['ru' => 'Тарифы', 'uz' => 'Tariflar', 'en' => 'Tariffs'], 'url' => $this->url('/cdma#cdma-tariffs')],
                ['key' => 'services', 'name' => ['ru' => 'Услуги', 'uz' => 'Xizmatlar', 'en' => 'Services'], 'url' => $this->url('/cdma#cdma-services')],
                ['key' => 'numbers', 'name' => ['ru' => 'Номера', 'uz' => 'Raqamlar', 'en' => 'Numbers'], 'url' => $this->url('/cdma#cdma-numbers')],
                ['key' => 'faq', 'name' => ['ru' => 'FAQ', 'uz' => 'FAQ', 'en' => 'FAQ'], 'url' => $this->url('/cdma#cdma-faq')],
                ['key' => 'support', 'name' => ['ru' => 'Поддержка', 'uz' => 'Qoʻllab-quvvatlash', 'en' => 'Support'], 'url' => $this->url('/cdma#cdma-support')],
                ['key' => 'news', 'name' => ['ru' => 'Новости', 'uz' => 'Yangiliklar', 'en' => 'News'], 'url' => $this->url('/cdma#cdma-news')],
                ['key' => 'actions', 'name' => ['ru' => 'Акции', 'uz' => 'Aksiyalar', 'en' => 'Promotions'], 'url' => $this->url('/cdma/actions')],
                ['key' => 'dealers', 'name' => ['ru' => 'Дилеры', 'uz' => 'Dilerlar', 'en' => 'Dealers'], 'url' => $this->url('/cdma/dealers')],
            ],

            MenuLocation::CdmaFooter->value => [
                [
                    'key' => 'cdma',
                    'name' => ['ru' => 'CDMA', 'uz' => 'CDMA', 'en' => 'CDMA'],
                    'column' => 1,
                    'children' => [
                        ['key' => 'tariffs', 'name' => ['ru' => 'Тарифы', 'uz' => 'Tariflar', 'en' => 'Tariffs'], 'url' => $this->url('/cdma#cdma-tariffs')],
                        ['key' => 'connect', 'name' => ['ru' => 'Как подключиться', 'uz' => 'Qanday ulanish kerak', 'en' => 'How to connect'], 'url' => $this->url('/cdma/connect')],
                        ['key' => 'dealers', 'name' => ['ru' => 'Дилеры', 'uz' => 'Dilerlar', 'en' => 'Dealers'], 'url' => $this->url('/cdma/dealers')],
                    ],
                ],
                [
                    'key' => 'company',
                    'name' => ['ru' => 'Компания', 'uz' => 'Kompaniya', 'en' => 'Company'],
                    'column' => 2,
                    'children' => [
                        ['key' => 'about', 'name' => ['ru' => 'О компании', 'uz' => 'Kompaniya haqida', 'en' => 'About us'], 'url' => $this->url('/about-company')],
                        ['key' => 'contacts', 'name' => ['ru' => 'Контакты', 'uz' => 'Kontaktlar', 'en' => 'Contacts'], 'url' => $this->url('/contacts')],
                        ['key' => 'documents', 'name' => ['ru' => 'Юридические документы', 'uz' => 'Yuridik hujjatlar', 'en' => 'Legal documents'], 'url' => $this->url('/documents')],
                    ],
                ],
                [
                    'key' => 'five-g',
                    'name' => ['ru' => 'Perfectum 5G', 'uz' => 'Perfectum 5G', 'en' => 'Perfectum 5G'],
                    'column' => 3,
                    'children' => [
                        ['key' => 'home', 'name' => ['ru' => 'Главная 5G', 'uz' => '5G bosh sahifa', 'en' => '5G home'], 'url' => $this->url('/')],
                        ['key' => 'tariffs', 'name' => ['ru' => 'Тарифы 5G', 'uz' => '5G tariflari', 'en' => '5G tariffs'], 'url' => $this->url('/tariffs')],
                        ['key' => 'coverage', 'name' => ['ru' => 'Карта покрытия', 'uz' => 'Qamrov xaritasi', 'en' => 'Coverage map'], 'url' => $this->url('/coverage-area')],
                    ],
                ],
            ],
        ];
    }

    /**
     * The same address in every language — the locale prefix is added by the
     * site, not stored here.
     *
     * @return array<string, string>
     */
    private function url(string $path): array
    {
        return collect(config('app.locales', [config('app.locale')]))
            ->mapWithKeys(fn (string $locale): array => [$locale => $path])
            ->all();
    }
}
