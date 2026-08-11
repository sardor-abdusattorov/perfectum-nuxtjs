<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Social;
use Illuminate\Database\Seeder;

class SocialSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->networks() as $sort => $network) {
            Social::updateOrCreate(
                ['url' => $network['url']],
                [
                    'name' => $network['name'],
                    'icon' => $network['icon'],
                    'sort' => $sort + 1,
                    'status' => true,
                ]
            );
        }
    }

    /**
     * @return array<int, array{name: string, icon: string, url: string}>
     */
    private function networks(): array
    {
        return [
            [
                'name' => 'Facebook',
                'icon' => 'simple-icons:facebook',
                'url' => 'https://www.facebook.com/Perfectum.Uzbekistan',
            ],
            [
                'name' => 'Instagram',
                'icon' => 'simple-icons:instagram',
                'url' => 'https://www.instagram.com/perfectum_5g_uz',
            ],
            [
                'name' => 'Telegram',
                'icon' => 'simple-icons:telegram',
                'url' => 'https://t.me/PerfectumUZ',
            ],
            [
                'name' => 'LinkedIn',
                'icon' => 'simple-icons:linkedin',
                'url' => 'https://www.linkedin.com/company/perfectum-mob',
            ],
        ];
    }
}
