<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ApplicationTheme;
use Illuminate\Database\Seeder;

class ApplicationThemeSeeder extends Seeder
{
    /**
     * The subjects the contact form used to carry in its own markup.
     *
     * @var array<int, array<string, string>>
     */
    private const THEMES = [
        ['ru' => 'Подключение', 'uz' => 'Ulanish'],
        ['ru' => 'Тарифы и оплата', 'uz' => 'Tariflar va toʻlov'],
        ['ru' => 'Техническая поддержка', 'uz' => 'Texnik yordam'],
        ['ru' => 'Другое', 'uz' => 'Boshqa'],
    ];

    public function run(): void
    {
        $sort = 0;

        foreach (self::THEMES as $name) {
            ApplicationTheme::updateOrCreate(['name->ru' => $name['ru']], [
                'name' => $name,
                'sort' => ++$sort,
            ]);
        }
    }
}
