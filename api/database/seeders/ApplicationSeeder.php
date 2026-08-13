<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Application;
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

        collect($rows)
            ->map(fn (array $row): array => [
                'name' => $row['name'],
                'phone' => $row['phone'],
                'email' => $row['email'],
                'theme' => Application::THEME_CONNECTION,
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
