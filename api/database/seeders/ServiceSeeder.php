<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Network;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * The home-internet and 5G SA mobile services belong to the 5G side of
     * the site; everything else the old site offered is the CDMA legacy.
     */
    private const FIVE_G_CATEGORIES = [
        'uslugi-dlya-domasnego-interneta',
        'uslugi-mobilnoi-svyazi-5g-sa',
    ];

    public function run(): void
    {
        $data = json_decode((string) file_get_contents(database_path('data/services.json')), true);

        $categories = $this->categoriesBySlug();

        $this->assignCategoryNetworks($categories);

        foreach ($data['services'] ?? [] as $row) {
            Service::updateOrCreate(['slug' => $row['slug']], [
                'category_id' => $categories[$row['category']] ?? null,
                'network' => $this->network($row['category']),
                'name' => $row['name'],
                'excerpt' => $row['excerpt'] ?: null,
                'lead' => $row['lead'] ?: null,
                'content' => $row['content'] ?: null,
                'price' => $row['price'] ?: null,
                'icon' => $row['icon'] ?? null,
                'image' => $row['image'] ?? null,
                'sort' => $row['sort'],
                'status' => true,
            ]);
        }
    }

    private function network(string $categorySlug): Network
    {
        return in_array($categorySlug, self::FIVE_G_CATEGORIES, true) ? Network::FiveG : Network::Cdma;
    }

    /**
     * @param  array<string, int>  $categories
     */
    private function assignCategoryNetworks(array $categories): void
    {
        foreach ($categories as $slug => $id) {
            ServiceCategory::query()->whereKey($id)->update(['network' => $this->network($slug)->value]);
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
