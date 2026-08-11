<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\SiteSettings;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->values() as $name => $value) {
            SiteSettings::updateOrCreate(['name' => $name], ['value' => $value, 'is_published' => true]);
        }
    }

    /**
     * @return array<string, string>
     */
    private function values(): array
    {
        return [
            'account_url' => 'https://my.perfectum.uz',
            'cdma_account_url' => 'https://my.perfectum.uz/cdma',

            'phone_primary' => '+998 98 127 0077',
            'phone_secondary' => '+998 98 305 1111',
            'phone_short' => '077',

            'telegram' => '@Perfectum_Support',
            'telegram_url' => 'https://t.me/Perfectum_Support',

            'email_info' => 'info@perfectum.uz',
            'email_hotline' => 'hotline@perfectum.uz',

            'map_url' => 'https://yandex.uz/maps/-/CDvOZK1p',

            'google_play_url' => 'https://play.google.com/store/apps/details?id=uz.perfectum',
            'app_store_url' => 'https://apps.apple.com/uz/app/perfectum/id1234567890',
        ];
    }
}
