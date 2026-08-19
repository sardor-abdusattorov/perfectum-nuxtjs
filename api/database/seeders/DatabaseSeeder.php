<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            SettingsSeeder::class,
            SiteSettingsSeeder::class,
            SiteTranslationSeeder::class,
            SocialSeeder::class,
            PageSettingsSeeder::class,
            TaxonomySeeder::class,
            TariffSeeder::class,
            ServiceSeeder::class,
            OfficeSeeder::class,
            FaqSeeder::class,
            FreeNumberFilterSeeder::class,
            ApplicationThemeSeeder::class,
            ApplicationSeeder::class,
            ContentSeeder::class,
            PageSeeder::class,
            MenuSeeder::class,
            HomepageSeeder::class,
            AboutCompanySeeder::class,
            ContactsSeeder::class,
            CdmaConnectSeeder::class,
            CdmaSeeder::class,
            DocumentSeeder::class,
            CoverageSeeder::class,
            DeviceSeeder::class,
        ]);

        // the old site's uploads ride along whenever the folder is in place
        if (is_dir(storage_path('app/old_files/public'))) {
            Artisan::call('old-files:import', [], $this->command?->getOutput());
        }
    }
}
