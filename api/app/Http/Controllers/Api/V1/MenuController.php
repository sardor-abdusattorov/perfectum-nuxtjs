<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Enums\MenuLocation;
use App\Http\Resources\V1\MenuResource;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class MenuController
{
    public function __invoke(): JsonResponse
    {
        $locale = app()->getLocale();

        $menus = collect(MenuLocation::cases())
            ->mapWithKeys(fn (MenuLocation $location): array => [
                $location->value => Cache::remember(
                    Menu::cacheKey($location, $locale),
                    Menu::CACHE_TTL,
                    fn (): array => MenuResource::collection(Menu::tree($location))->resolve(),
                ),
            ]);

        return response()->json(['data' => $menus]);
    }
}
