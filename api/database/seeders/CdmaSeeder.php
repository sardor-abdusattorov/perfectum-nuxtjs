<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ContactCardIcon;
use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Models\ContentBlock;
use Illuminate\Database\Seeder;

class CdmaSeeder extends Seeder
{
    public function run(): void
    {
        ContentBlock::write(PageKey::Cdma, ContentBlockKey::PageHero, [
            'title' => ['ru' => 'CDMA', 'uz' => 'CDMA'],
            'subtitle' => [
                'ru' => 'Всё для действующих и новых абонентов — в одном месте.',
                'uz' => 'Amaldagi va yangi abonentlar uchun hamma narsa — bir joyda.',
            ],
        ]);

        ContentBlock::write(PageKey::Cdma, ContentBlockKey::Support, [
            'cards' => [
                [
                    'icon' => ContactCardIcon::Phone->value,
                    'title' => ['ru' => 'Круглосуточно', 'uz' => 'Kecha-kunduz'],
                    'value' => ['ru' => '077', 'uz' => '077'],
                    'url' => 'tel:077',
                    'note' => [
                        'ru' => 'Бесплатно с любого номера Perfectum',
                        'uz' => 'Istalgan Perfectum raqamidan bepul',
                    ],
                    'status' => true,
                ],
                [
                    'icon' => ContactCardIcon::Mobile->value,
                    'title' => ['ru' => 'С других номеров', 'uz' => 'Boshqa raqamlardan'],
                    'value' => ['ru' => '+998 98 127 0077', 'uz' => '+998 98 127 0077'],
                    'url' => 'tel:+998981270077',
                    'note' => ['ru' => 'Стандартная тарификация', 'uz' => 'Standart tariflash'],
                    'status' => true,
                ],
                [
                    'icon' => ContactCardIcon::Chat->value,
                    'title' => ['ru' => 'Чат в Telegram', 'uz' => 'Telegram’da chat'],
                    'value' => ['ru' => '@Perfectum_Support', 'uz' => '@Perfectum_Support'],
                    'url' => 'https://t.me/Perfectum_Support',
                    'note' => ['ru' => 'Ответ в течение 15 минут', 'uz' => '15 daqiqa ichida javob'],
                    'status' => true,
                ],
                [
                    'icon' => ContactCardIcon::MapPin->value,
                    'title' => ['ru' => 'Офисы обслуживания', 'uz' => 'Xizmat koʻrsatish ofislari'],
                    'value' => ['ru' => 'Найти ближайший →', 'uz' => 'Eng yaqinini topish →'],
                    'note' => ['ru' => '18 офисов + 987 дилеров', 'uz' => '18 ofis + 987 diler'],
                    'url' => '/offices',
                    'status' => true,
                ],
            ],
        ]);

        ContentBlock::write(PageKey::Cdma, ContentBlockKey::Cta, [
            'kicker' => ['ru' => 'Когда будете готовы', 'uz' => 'Tayyor boʻlganingizda'],
            'title' => ['ru' => 'Готовы к 5G?', 'uz' => '5G ga tayyormisiz?'],
            'text' => [
                'ru' => 'Скорости до 1 Гбит/с, VoNR-звонки, eSIM и домашний интернет без проводов — всё, чего нет на CDMA.',
                'uz' => '1 Gbit/s gacha tezlik, VoNR-qoʻngʻiroqlar, eSIM va simsiz uy interneti — CDMA’da yoʻq hamma narsa.',
            ],
            'primary_label' => ['ru' => 'Узнать о 5G', 'uz' => '5G haqida bilish'],
            'primary_url' => '/',
            'ghost_label' => ['ru' => 'Проверить покрытие', 'uz' => 'Qamrovni tekshirish'],
            'ghost_url' => '/coverage-area',
        ]);
    }
}
