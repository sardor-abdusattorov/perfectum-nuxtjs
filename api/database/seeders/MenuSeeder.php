<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\MenuLocation;
use App\Models\Menu;
use Illuminate\Database\Seeder;

/**
 * The tree mirrors the menus table of the old site, item for item; only the
 * addresses are translated to where each section lives now.
 *
 * A section that only gathers other pages carries no address of its own — the
 * header opens its list instead of going anywhere, and «Тарифы» inside it is
 * the very page the heading used to lead to.
 */
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
                    'open_in_new_tab' => $item['blank'] ?? false,
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
                        ['key' => 'services', 'name' => ['ru' => 'Услуги', 'uz' => 'Xizmatlar'], 'url' => $this->url('/services')],
                        ['key' => 'numbers', 'name' => ['ru' => 'Свободные номера', 'uz' => 'Boʻsh raqamlar'], 'url' => $this->url('/numbers')],
                        ['key' => 'actions', 'name' => ['ru' => 'Акции', 'uz' => 'Aksiyalar'], 'url' => $this->url('/actions')],
                        ['key' => 'faq', 'name' => ['ru' => 'FAQ', 'uz' => 'FAQ'], 'url' => $this->url('/faq')],
                    ],
                ],
                [
                    'key' => 'home',
                    'name' => ['ru' => 'Домашний интернет', 'uz' => 'Uy interneti'],
                    'children' => [
                        ['key' => 'tariffs', 'name' => ['ru' => 'Тарифы', 'uz' => 'Tariflar'], 'url' => $this->url('/tariffs?tab=2')],
                        ['key' => 'services', 'name' => ['ru' => 'Услуги', 'uz' => 'Xizmatlar'], 'url' => $this->url('/services')],
                        ['key' => 'devices', 'name' => ['ru' => 'Каталог устройств', 'uz' => 'Uskunalar katalogi'], 'url' => $this->url('/devices')],
                        ['key' => 'actions', 'name' => ['ru' => 'Акции', 'uz' => 'Aksiyalar'], 'url' => $this->url('/actions')],
                        ['key' => 'faq', 'name' => ['ru' => 'FAQ', 'uz' => 'FAQ'], 'url' => $this->url('/faq')],
                    ],
                ],
                [
                    'key' => 'cdma',
                    'name' => ['ru' => 'CDMA', 'uz' => 'CDMA'],
                    'url' => $this->url('/cdma'),
                    'children' => [
                        ['key' => 'tariffs', 'name' => ['ru' => 'Тарифы', 'uz' => 'Tariflar'], 'url' => $this->url('/cdma#cdma-tariffs')],
                        ['key' => 'services', 'name' => ['ru' => 'Услуги', 'uz' => 'Xizmatlar'], 'url' => $this->url('/cdma#cdma-services')],
                        ['key' => 'numbers', 'name' => ['ru' => 'Свободные номера', 'uz' => 'Boʻsh raqamlar'], 'url' => $this->url('/help/numbers')],
                        ['key' => 'faq', 'name' => ['ru' => 'FAQ', 'uz' => 'FAQ'], 'url' => $this->url('/cdma#cdma-faq')],
                        ['key' => 'devices', 'name' => ['ru' => 'Каталог устройств', 'uz' => 'Uskunalar katalogi'], 'url' => $this->url('/devices')],
                        ['key' => 'account', 'name' => ['ru' => 'Личный кабинет CDMA', 'uz' => 'Shaxsiy kabinet CDMA'], 'url' => $this->url('https://my.perfectum.uz/'), 'blank' => true],
                    ],
                ],
                ['key' => 'coverage', 'name' => ['ru' => 'Карта покрытия', 'uz' => 'Qamrov xaritasi'], 'url' => $this->url('/coverage-area')],
                ['key' => 'news', 'name' => ['ru' => 'Новости', 'uz' => 'Yangiliklar'], 'url' => $this->url('/news')],
            ],
            MenuLocation::Footer->value => [
                [
                    'key' => 'mobile',
                    'name' => ['ru' => 'Мобильная связь', 'uz' => 'Abonentlarga'],
                    'children' => [
                        ['key' => 'tariffs', 'name' => ['ru' => 'Тарифы', 'uz' => 'Tariflar'], 'url' => $this->url('/tariffs')],
                        ['key' => 'services', 'name' => ['ru' => 'Услуги', 'uz' => 'Xizmatlar'], 'url' => $this->url('/services')],
                        ['key' => 'numbers', 'name' => ['ru' => 'Свободные номера', 'uz' => 'Boʻsh raqamlar'], 'url' => $this->url('/numbers')],
                        ['key' => 'devices', 'name' => ['ru' => 'Устройства', 'uz' => 'Uskunalar'], 'url' => $this->url('/devices')],
                        ['key' => 'coverage', 'name' => ['ru' => 'Зона покрытия', 'uz' => 'Qamrov hududi'], 'url' => $this->url('/coverage-area')],
                    ],
                ],
                [
                    'key' => 'home-internet',
                    'name' => ['ru' => 'Домашний интернет', 'uz' => 'Uy interneti'],
                    'children' => [
                        ['key' => 'coverage', 'name' => ['ru' => 'Карта покрытия', 'uz' => 'Qamrov zonasi'], 'url' => $this->url('/coverage-area')],
                        ['key' => 'account', 'name' => ['ru' => 'Личный кабинет', 'uz' => 'Shaxsiy kabinet'], 'url' => $this->url('https://lk.perfectum.uz/'), 'blank' => true],
                    ],
                ],
                [
                    'key' => 'useful',
                    'name' => ['ru' => 'Полезное', 'uz' => 'Foydali'],
                    'children' => [
                        ['key' => 'actions', 'name' => ['ru' => 'Акции', 'uz' => 'Aksiyalar'], 'url' => $this->url('/actions')],
                        ['key' => 'news', 'name' => ['ru' => 'Новости', 'uz' => 'Yangiliklar'], 'url' => $this->url('/news')],
                        ['key' => 'offices', 'name' => ['ru' => 'Офисы', 'uz' => 'Ofislar'], 'url' => $this->url('/offices')],
                        ['key' => 'dealers', 'name' => ['ru' => 'Дилеры CDMA', 'uz' => 'CDMA Dilerlar'], 'url' => $this->url('/cdma/dealers')],
                        ['key' => 'connect', 'name' => ['ru' => 'Как подключиться', 'uz' => 'Qanday ulanish kerak'], 'url' => $this->url('/pages/kak-mozno-podklyucitsya')],
                        ['key' => 'know', 'name' => ['ru' => 'Полезно знать', 'uz' => 'Bilish foydali'], 'url' => $this->url('/pages/o-standarte-cdma')],
                    ],
                ],
                [
                    'key' => 'information',
                    'name' => ['ru' => 'Информация', 'uz' => 'Maʼlumot'],
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
