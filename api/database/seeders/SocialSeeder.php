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
                ['name' => $network['name']],
                [
                    'url' => $network['url'],
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
                'icon' => 'si-facebook',
                'url' => 'https://www.facebook.com/Perfectum.Uzbekistan',
            ],
            [
                'name' => 'Instagram',
                'icon' => 'si-instagram',
                'url' => 'https://www.instagram.com/perfectum.uzbekistan/',
            ],
            [
                'name' => 'Telegram',
                'icon' => 'si-telegram',
                'url' => 'https://t.me/PerfectumUZ',
            ],
            [
                'name' => 'LinkedIn',
                'icon' => 'brand-linkedin',
                'url' => 'https://www.linkedin.com/company/perfectum-mob',
            ],
        ];
    }
}
