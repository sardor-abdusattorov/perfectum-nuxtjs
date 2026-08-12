<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->values() as $key => $value) {
            Settings::set($key, $value);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function values(): array
    {
        return [
            'seo.title' => [
                'ru' => 'Perfectum — оператор связи 5G Standalone в Узбекистане',
                'uz' => "Perfectum — O'zbekistondagi 5G Standalone aloqa operatori",
                'en' => 'Perfectum — the 5G Standalone network operator in Uzbekistan',
            ],

            'seo.description' => [
                'ru' => 'Perfectum — высокоскоростной мобильный и домашний интернет на сети 5G Standalone. Тарифы, красивые номера и покрытие по всему Узбекистану.',
                'uz' => "Perfectum — 5G Standalone tarmog'iga asoslangan yuqori tezlikdagi mobil va uy interneti. Tariflar, chiroyli raqamlar va butun O'zbekiston bo'ylab qamrov.",
                'en' => 'Perfectum — high-speed mobile and home internet on a 5G Standalone network. Tariffs, premium numbers and coverage across Uzbekistan.',
            ],

            'seo.keywords' => [
                'ru' => 'Perfectum, 5G, интернет, мобильная связь, тарифы, Узбекистан',
                'uz' => "Perfectum, 5G, internet, mobil aloqa, tariflar, O'zbekiston",
                'en' => 'Perfectum, 5G, internet, mobile, tariffs, Uzbekistan',
            ],

            'seo.indexing_enabled' => true
        ];
    }
}
