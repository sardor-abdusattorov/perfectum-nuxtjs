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
     * @var array<string, array<string, string>>
     */
    private const THEMES = [
        'podklyuchenie' => ['ru' => 'Подключение', 'uz' => 'Ulanish'],
        'tarify-i-oplata' => ['ru' => 'Тарифы и оплата', 'uz' => 'Tariflar va toʻlov'],
        'tehnicheskaya-podderzhka' => ['ru' => 'Техническая поддержка', 'uz' => 'Texnik yordam'],
        'drugoe' => ['ru' => 'Другое', 'uz' => 'Boshqa'],
    ];

    public function run(): void
    {
        $sort = 0;

        foreach (self::THEMES as $slug => $name) {
            ApplicationTheme::updateOrCreate(['slug' => $slug], [
                'name' => $name,
                'sort' => ++$sort,
            ]);
        }
    }
}
