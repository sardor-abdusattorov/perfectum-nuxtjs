<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Models\ContentBlock;
use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * The files themselves are uploaded from the admin, one per language, so
     * the rows carry the names the page listed and wait for their attachment.
     *
     * @var array<string, array<int, array<string, string>>>
     */
    private const DOCUMENTS = [
        'dogovory-i-tarify' => [
            ['ru' => 'Публичная оферта на оказание услуг', 'uz' => 'Xizmatlar koʻrsatish boʻyicha ommaviy oferta'],
            ['ru' => 'Договор на услуги домашнего интернета', 'uz' => 'Uy interneti xizmatlari shartnomasi'],
            ['ru' => 'Тарифы и расценки на связь', 'uz' => 'Aloqa tariflari va narxlari'],
        ],
        'politiki' => [
            ['ru' => 'Политика обработки персональных данных', 'uz' => 'Shaxsiy maʼlumotlarni qayta ishlash siyosati'],
            ['ru' => 'Политика конфиденциальности', 'uz' => 'Maxfiylik siyosati'],
        ],
        'korporativnye-dokumenty' => [
            ['ru' => 'Устав компании (выписка)', 'uz' => 'Kompaniya ustavi (koʻchirma)'],
            ['ru' => 'Реквизиты организации', 'uz' => 'Tashkilot rekvizitlari'],
        ],
    ];

    /**
     * @var array<string, array<string, string>>
     */
    private const CATEGORIES = [
        'dogovory-i-tarify' => ['ru' => 'Договоры и тарифы', 'uz' => 'Shartnomalar va tariflar'],
        'politiki' => ['ru' => 'Политики', 'uz' => 'Siyosatlar'],
        'korporativnye-dokumenty' => ['ru' => 'Корпоративные документы', 'uz' => 'Korporativ hujjatlar'],
    ];

    public function run(): void
    {
        ContentBlock::write(PageKey::Documents, ContentBlockKey::PageHero, [
            'eyebrow' => ['ru' => 'Документация', 'uz' => 'Hujjatlar'],
            'title' => [
                'ru' => '<span class="color" data-color="accent">Документы</span>',
                'uz' => '<span class="color" data-color="accent">Hujjatlar</span>',
            ],
            'subtitle' => [
                'ru' => 'Договоры, тарифы и политики компании в открытом доступе.',
                'uz' => 'Kompaniya shartnomalari, tariflari va siyosatlari ochiq foydalanishda.',
            ],
        ]);

        $sort = 0;

        $categories = [];

        foreach (self::CATEGORIES as $key => $name) {
            $categories[$key] = DocumentCategory::updateOrCreate(
                ['name->ru' => $name['ru']],
                ['name' => $name, 'sort' => ++$sort],
            )->getKey();
        }

        foreach (self::DOCUMENTS as $key => $documents) {
            foreach ($documents as $index => $name) {
                Document::updateOrCreate(['name->ru' => $name['ru']], [
                    'category_id' => $categories[$key] ?? null,
                    'name' => $name,
                    'sort' => $index + 1,
                    'status' => false,
                ]);
            }
        }
    }
}
