<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ContactCard;
use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Models\ContentBlock;
use Illuminate\Database\Seeder;

class ContactsSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->blocks() as $key => $data) {
            ContentBlock::write(PageKey::Contacts, ContentBlockKey::from($key), $data);
        }
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function blocks(): array
    {
        return [
            'page_hero' => [
                'eyebrow' => ['ru' => 'Свяжитесь с нами', 'uz' => 'Biz bilan bogʻlaning'],
                'title' => ['ru' => 'Контакты', 'uz' => 'Kontaktlar'],
                'subtitle' => [
                    'ru' => 'Офисы, телефоны, почта и соцсети Perfectum.',
                    'uz' => 'Perfectum ofislari, telefonlari, pochtasi va ijtimoiy tarmoqlari.',
                ],
            ],

            'cards' => [
                'items' => [
                    [
                        'type' => ContactCard::Office->value,
                        'title' => ['ru' => 'Центральный офис', 'uz' => 'Bosh ofis'],
                        'note' => ['ru' => 'Пн–Вс 09:00–21:00', 'uz' => 'Du–Ya 09:00–21:00'],
                    ],
                    [
                        'type' => ContactCard::Phones->value,
                        'title' => ['ru' => 'Контакт-центр', 'uz' => 'Aloqa markazi'],
                        'note' => [
                            'ru' => '077 — бесплатно с номеров Perfectum',
                            'uz' => '077 — Perfectum raqamlaridan bepul',
                        ],
                    ],
                    [
                        'type' => ContactCard::Emails->value,
                        'title' => ['ru' => 'Почта и реквизиты', 'uz' => 'Pochta va rekvizitlar'],
                        'note' => ['ru' => '', 'uz' => ''],
                    ],
                    [
                        'type' => ContactCard::Socials->value,
                        'title' => ['ru' => 'Мы в соцсетях', 'uz' => 'Ijtimoiy tarmoqlarda'],
                        'note' => [
                            'ru' => 'Будьте на связи и следите за новостями компании',
                            'uz' => 'Aloqada boʻling va kompaniya yangiliklarini kuzating',
                        ],
                    ],
                ],

                'note' => [
                    'ru' => '<p>Полный список офисов продаж и дилеров — на странице <a href="/offices">«Офисы»</a>.</p>',
                    'uz' => '<p>Savdo ofislari va dilerlarning toʻliq roʻyxati — <a href="/offices">«Ofislar»</a> sahifasida.</p>',
                ],
            ],
        ];
    }
}
