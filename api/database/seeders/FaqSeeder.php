<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $data = json_decode((string) file_get_contents(database_path('data/faqs.json')), true);

        $categories = [];

        foreach ($data['categories'] ?? [] as $sort => $category) {
            $categories[$category['slug']] = FaqCategory::updateOrCreate(
                ['name->ru' => $category['name']['ru']],
                ['name' => $category['name'], 'sort' => $sort + 1],
            )->getKey();
        }

        foreach ($data['faqs'] ?? [] as $row) {
            Faq::updateOrCreate(['question->ru' => $row['question']['ru']], [
                'category_id' => $categories[$row['category']] ?? null,
                'question' => $row['question'],
                'answer' => $row['answer'],
                'pages' => $row['pages'],
                'sort' => $row['sort'],
            ]);
        }
    }
}
