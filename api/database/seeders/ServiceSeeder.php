<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $data = json_decode((string) file_get_contents(database_path('data/services.json')), true);

        $categories = $this->categoriesBySlug();

        foreach ($data['services'] ?? [] as $row) {
            Service::updateOrCreate(['slug' => $row['slug']], [
                'category_id' => $categories[$row['category']] ?? null,
                'name' => $row['name'],
                'excerpt' => $row['excerpt'] ?: null,
                'lead' => $row['lead'] ?: null,
                'content' => $row['content'] ?: null,
                'price' => $row['price'] ?: null,
                'sort' => $row['sort'],
                'status' => true,
            ]);
        }
    }

    /**
     * The dump names a category by its slug, which the table no longer keeps,
     * so the join goes through the taxonomy data file: its slug names the same
     * row the category seeder created under that name.
     *
     * @return array<string, int>
     */
    private function categoriesBySlug(): array
    {
        $taxonomies = json_decode((string) file_get_contents(database_path('data/taxonomies.json')), true);

        $byName = ServiceCategory::query()
            ->get()
            ->mapWithKeys(fn (ServiceCategory $category): array => [
                $category->getTranslation('name', 'ru') => $category->getKey(),
            ]);

        return collect($taxonomies['service_types'] ?? [])
            ->mapWithKeys(fn (array $type): array => [
                $type['slug'] => $byName[$type['name']['ru']] ?? null,
            ])
            ->filter()
            ->all();
    }
}
