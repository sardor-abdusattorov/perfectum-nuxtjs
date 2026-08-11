<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Models\Settings;
use App\Models\SiteSettings;
use Illuminate\Http\JsonResponse;

class SettingsController
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'data' => [
                'locale' => app()->getLocale(),
                'locales' => config('app.locales', [config('app.locale')]),
                'seo' => Settings::seo(),
                'metrics' => [
                    'enabled' => filled(Settings::get('metrics.yandex'))
                        || filled(Settings::get('metrics.google')),
                ],
                'site' => SiteSettings::published(),
            ],
        ]);
    }
}
