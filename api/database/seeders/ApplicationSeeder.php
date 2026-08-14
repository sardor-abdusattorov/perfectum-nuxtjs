<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Application;
use App\Models\ApplicationTheme;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        if (Application::query()->exists()) {
            return;
        }

        $rows = json_decode((string) file_get_contents(database_path('data/applications.json')), true);

        // the dump carries no subject of its own, so the imported rows land
        // under the first one the admin can rearrange them from
        $theme = ApplicationTheme::query()->ordered()->value('id');

        collect($rows)
            ->map(fn (array $row): array => [
                'name' => $row['name'],
                'phone' => $row['phone'],
                'email' => $row['email'],
                'theme_id' => $theme,
                'message' => $row['message'],
                'status' => Application::STATUS_PROCESSED,
                'ip_address' => null,
                'created_at' => $row['created_at'],
                'updated_at' => $row['created_at'],
            ])
            ->chunk(500)
            ->each(fn ($chunk) => DB::table('applications')->insert($chunk->all()));
    }
}
