<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Support\Content;
use Illuminate\Database\Seeder;

class HomepageSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->blocks() as $key => $data) {
            Content::save(PageKey::Home, ContentBlockKey::from($key), $data);
        }
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function blocks(): array
    {
        return [];
    }
}
