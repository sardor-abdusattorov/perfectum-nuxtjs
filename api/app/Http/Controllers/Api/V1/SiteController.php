<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Enums\MenuLocation;
use App\Http\Resources\V1\MenuResource;
use App\Http\Resources\V1\SocialResource;
use App\Models\Menu;
use App\Models\Settings;
use App\Models\SiteSettings;
use App\Models\SiteTranslation;
use App\Models\Social;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class SiteController
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'data' => [
                'settings' => $this->settings(),
                'menus' => $this->menus(),
                'socials' => $this->socials(),
                'translations' => SiteTranslation::flat(),
            ],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function settings(): array
    {
        return [
            'locale' => app()->getLocale(),
            'locales' => app_locales(),
            'seo' => Settings::seo(),
            'metrics' => [
                'enabled' => filled(Settings::get('metrics.yandex'))
                    || filled(Settings::get('metrics.google')),
            ],
            'site' => SiteSettings::published(),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function socials(): array
    {
        return Cache::remember(
            Social::cacheKey(),
            Social::CACHE_TTL,
            fn (): array => SocialResource::collection(
                Social::query()->published()->ordered()->get()
            )->resolve(),
        );
    }

    /**
     * @return array<string, array<int, array<string, mixed>>>
     */
    private function menus(): array
    {
        $locale = app()->getLocale();

        return collect(MenuLocation::cases())
            ->mapWithKeys(fn (MenuLocation $location): array => [
                $location->value => Cache::remember(
                    Menu::cacheKey($location, $locale),
                    Menu::CACHE_TTL,
                    fn (): array => MenuResource::collection(Menu::tree($location))->resolve(),
                ),
            ])
            ->all();
    }
}
