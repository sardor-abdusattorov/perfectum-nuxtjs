<?php

use App\Enums\MenuLocation;
use App\Enums\Network;
use App\Enums\PageKey;
use App\Models\ActionCategory;
use App\Models\ApplicationTheme;
use App\Models\ContentBlock;
use App\Models\DeviceCategory;
use App\Models\DocumentCategory;
use App\Models\FaqCategory;
use App\Models\Menu;
use App\Models\NewsCategory;
use App\Models\Page;
use App\Models\Region;
use App\Models\ServiceCategory;
use App\Models\Settings;
use App\Models\SiteSettings;
use App\Models\SiteTranslation;
use App\Models\Social;
use App\Models\TariffCategory;
use App\Models\TariffType;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

if (! function_exists('app_locales')) {
    /**
     * @return array<int, string>
     */
    function app_locales(): array
    {
        return config('app.locales', [config('app.locale')]);
    }
}

if (! function_exists('stored_url')) {
    /**
     * A row can outlive its file — a database restored without the storage
     * folder leaves the column pointing at nothing. Answering null there lets
     * the frontend fall back instead of laying out a broken image.
     */
    function stored_url(mixed $path): ?string
    {
        if (! is_string($path) || blank($path)) {
            return null;
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        $disk = Storage::disk('public');

        return $disk->exists($path) ? $disk->url($path) : null;
    }
}

if (! function_exists('settings')) {
    function settings(string $key, mixed $default = null): mixed
    {
        return Settings::get($key, $default);
    }
}

if (! function_exists('clear_settings_cache')) {
    function clear_settings_cache(): void
    {
        Cache::forget(Settings::cacheKey());
        Settings::forgetValues();
    }
}

if (! function_exists('site_setting')) {
    function site_setting(string $name, mixed $default = null): mixed
    {
        return SiteSettings::get($name, $default);
    }
}

if (! function_exists('clear_site_settings_cache')) {
    function clear_site_settings_cache(): void
    {
        Cache::forget(SiteSettings::cacheKey());
    }
}

if (! function_exists('clear_pages_cache')) {
    function clear_pages_cache(?string $slug = null): void
    {
        $slugs = $slug !== null ? [$slug] : Page::query()->pluck('slug')->all();

        foreach ($slugs as $value) {
            foreach (app_locales() as $locale) {
                Cache::forget(Page::cacheKey($value, $locale));
            }
        }
    }
}

if (! function_exists('taxonomies')) {
    /**
     * @return array<int, class-string>
     */
    function taxonomies(): array
    {
        return [
            NewsCategory::class,
            ActionCategory::class,
            FaqCategory::class,
            DeviceCategory::class,
            TariffCategory::class,
            TariffType::class,
            ServiceCategory::class,
            DocumentCategory::class,
            ApplicationTheme::class,
            Region::class,
        ];
    }
}

if (! function_exists('clear_taxonomy_cache')) {
    function clear_taxonomy_cache(string $model): void
    {
        foreach (app_locales() as $locale) {
            Cache::forget($model::cacheKey($locale));

            foreach ([null, ...Network::cases()] as $network) {
                Cache::forget($model::publicCacheKey($locale, $network));
            }
        }
    }
}

if (! function_exists('clear_socials_cache')) {
    function clear_socials_cache(): void
    {
        Cache::forget(Social::cacheKey());
    }
}

if (! function_exists('clear_menus_cache')) {
    function clear_menus_cache(): void
    {
        foreach (MenuLocation::cases() as $location) {
            foreach (app_locales() as $locale) {
                Cache::forget(Menu::cacheKey($location, $locale));
            }
        }
    }
}

if (! function_exists('content_blocks')) {
    /**
     * @return array<string, array<string, mixed>>
     */
    function content_blocks(PageKey $page): array
    {
        $locale = app()->getLocale();

        return Cache::remember(
            ContentBlock::cacheKey($page, $locale),
            ContentBlock::CACHE_TTL,
            fn (): array => ContentBlock::query()
                ->page($page)
                ->get()
                ->mapWithKeys(fn (ContentBlock $block): array => [$block->key->value => $block->data])
                ->all(),
        );
    }
}

if (! function_exists('clear_content_blocks_cache')) {
    function clear_content_blocks_cache(?PageKey $page = null): void
    {
        $pages = $page !== null ? [$page] : PageKey::cases();

        foreach ($pages as $pageKey) {
            foreach (app_locales() as $locale) {
                Cache::forget(ContentBlock::cacheKey($pageKey, $locale));
            }
        }
    }
}

if (! function_exists('translator')) {
    /**
     * @param  array<string, string|int>  $replace
     */
    function translator(
        string $category,
        ?string $key = null,
        array $replace = [],
        ?string $locale = null
    ): string {
        if ($key === null && str_contains($category, '.')) {
            [$category, $key] = explode('.', $category, 2);
        }

        if ($key === null) {
            return $category;
        }

        $value = SiteTranslation::get($category, $key, $locale);

        if ($value === null) {
            return $key;
        }

        foreach ($replace as $k => $v) {
            $value = str_replace(':'.$k, (string) $v, $value);
        }

        return $value;
    }
}

if (! function_exists('clear_translator_cache')) {
    function clear_translator_cache(): void
    {
        foreach (app_locales() as $locale) {
            Cache::forget(SiteTranslation::cacheKey($locale));
        }
    }
}
