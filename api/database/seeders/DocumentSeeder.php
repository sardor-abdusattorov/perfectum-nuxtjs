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

        $data = json_decode((string) file_get_contents(database_path('data/documents.json')), true);

        foreach ($data['categories'] ?? [] as $sort => $category) {
            $categoryId = DocumentCategory::updateOrCreate(
                ['name->ru' => $category['name']['ru']],
                ['name' => $category['name'], 'sort' => $sort + 1],
            )->getKey();

            foreach ($category['documents'] as $index => $document) {
                Document::updateOrCreate(
                    ['name->ru' => $document['name']['ru'] ?? array_values($document['name'])[0]],
                    [
                        'category_id' => $categoryId,
                        'name' => $document['name'],
                        'file' => $document['file'],
                        'sort' => $index + 1,
                        'status' => true,
                    ],
                );
            }
        }
    }
}
