<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $data = json_decode((string) file_get_contents(database_path('data/content.json')), true);

        foreach ($data['pages'] ?? [] as $row) {
            Page::updateOrCreate(['slug' => $row['slug']], [
                'title' => $row['title'],
                'content' => $row['content'],
                'image' => $row['image'] ?? null,
                'redirect_from' => $row['redirect_from'] ?? [],
                'status' => true,
            ]);
        }
    }
}
