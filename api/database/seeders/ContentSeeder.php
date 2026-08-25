<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Network;
use App\Models\Action;
use App\Models\ActionCategory;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Tender;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $data = json_decode((string) file_get_contents(database_path('data/content.json')), true);

        $newsCategories = $this->categories(NewsCategory::class, $data['news_categories'] ?? []);
        $actionCategories = $this->categories(ActionCategory::class, $data['action_categories'] ?? []);

        foreach ($data['news'] ?? [] as $row) {
            News::updateOrCreate(['slug' => $row['slug']], [
                'category_id' => $newsCategories[$row['category']] ?? null,
                'network' => $this->newsNetwork($row['title']['ru'] ?? ''),
                'title' => $row['title'],
                'excerpt' => $row['excerpt'],
                'content' => $row['content'],
                'preview_image' => $row['image'] ?? null,
                'main_image' => $row['image'] ?? null,
                'is_featured' => $row['is_featured'],
                'published_at' => $row['published_at'],
            ]);
        }

        foreach ($data['actions'] ?? [] as $row) {
            Action::updateOrCreate(['slug' => $row['slug']], [
                'category_id' => $actionCategories[$row['category']] ?? null,
                'network' => $row['network'],
                'title' => $row['title'],
                'badge' => $row['badge'],
                'excerpt' => $row['excerpt'],
                'content' => $row['content'],
                'preview_image' => $row['image'] ?? null,
                'main_image' => $row['image'] ?? null,
                'starts_at' => $row['starts_at'],
                'ends_at' => $row['ends_at'],
            ]);
        }

        foreach ($data['vacancies'] ?? [] as $row) {
            Vacancy::updateOrCreate(['slug' => $row['slug']], [
                'title' => $row['title'],
                'department' => $row['department'],
                'city' => $row['city'],
                'employment' => $row['employment'],
                'salary' => $row['salary'],
                'content' => $row['content'],
                'sort' => $row['sort'],
            ]);
        }

        foreach ($data['tenders'] ?? [] as $row) {
            Tender::updateOrCreate(['slug' => $row['slug']], [
                'title' => $row['title'],
                'content' => $row['content'],
                'files' => $row['files'] ?? [],
                'state' => $row['state'],
                'deadline_at' => $row['deadline_at'],
                'published_at' => $row['published_at'] ?? null,
            ]);
        }
    }

    /**
     * The operator announces its maintenance windows on the 5G network, and
     * everything else the old site published — service numbers, prefixes and
     * the content providers' notices — is the CDMA feed. Each news item keeps
     * its own switch in the admin, so a wrong guess is one click to fix.
     */
    private function newsNetwork(string $title): Network
    {
        return preg_match('/техническ|профилактич/iu', $title) === 1
            ? Network::FiveG
            : Network::Cdma;
    }

    /**
     * @param  class-string  $model
     * @param  array<int, array<string, mixed>>  $rows
     * @return array<string, int>
     */
    private function categories(string $model, array $rows): array
    {
        $ids = [];

        foreach ($rows as $sort => $row) {
            $ids[$row['slug']] = $model::updateOrCreate(
                ['slug' => $row['slug']],
                ['name' => $row['name'], 'sort' => $sort + 1],
            )->getKey();
        }

        return $ids;
    }
}
