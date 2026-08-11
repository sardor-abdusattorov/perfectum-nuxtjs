<?php

use App\Enums\MenuLocation;
use App\Enums\PageKey;
use App\Models\ContentBlock;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Settings;
use App\Models\SiteSettings;
use App\Models\SiteTranslation;
use Illuminate\Support\Facades\Cache;

if (! function_exists('settings')) {
    function settings(string $key, mixed $default = null): mixed
    {
        return Settings::get($key, $default);
    }
}

if (! function_exists('clear_settings_cache')) {
    function clear_settings_cache(?string $key = null): void
    {
        if ($key !== null) {
            Cache::forget(Settings::cacheKey($key));

            return;
        }

        Settings::query()->pluck('key')->each(
            fn (string $k) => Cache::forget(Settings::cacheKey($k))
        );
    }
}

if (! function_exists('site_setting')) {
    function site_setting(string $name, mixed $default = null): mixed
    {
        return SiteSettings::get($name, $default);
    }
}

if (! function_exists('clear_site_settings_cache')) {
    function clear_site_settings_cache(?string $name = null): void
    {
        Cache::forget(SiteSettings::collectionCacheKey());

        if ($name !== null) {
            Cache::forget(SiteSettings::cacheKey($name));

            return;
        }

        SiteSettings::query()->pluck('name')->each(
            fn (string $n) => Cache::forget(SiteSettings::cacheKey($n))
        );
    }
}

if (! function_exists('clear_pages_cache')) {
    function clear_pages_cache(?string $slug = null): void
    {
        if ($slug !== null) {
            Cache::forget(Page::cacheKey($slug));

            return;
        }

        Page::query()->pluck('slug')->each(
            fn (string $value) => Cache::forget(Page::cacheKey($value))
        );
    }
}

if (! function_exists('clear_menus_cache')) {
    function clear_menus_cache(): void
    {
        $locales = config('app.locales', [config('app.locale')]);

        foreach (MenuLocation::cases() as $location) {
            foreach ($locales as $locale) {
                Cache::forget(Menu::cacheKey($location, $locale));
            }
        }
    }
}

if (! function_exists('content_blocks')) {
    /**
     * Every block of a page keyed by block key, already resolved to the
     * current locale.
     *
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
        $locales = config('app.locales', [config('app.locale')]);
        $pages = $page !== null ? [$page] : PageKey::cases();

        foreach ($pages as $pageKey) {
            foreach ($locales as $locale) {
                Cache::forget(ContentBlock::cacheKey($pageKey, $locale));
            }
        }
    }
}

if (! function_exists('translator')) {
    /**
     * Site translation by "category.key" or by separate arguments. Returns the
     * key itself when nothing is published under it.
     *
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
    function clear_translator_cache(?string $category = null, ?string $key = null): void
    {
        $locales = config('app.locales', [config('app.locale')]);

        foreach ($locales as $locale) {
            Cache::forget(SiteTranslation::collectionCacheKey($locale));
        }

        if ($category !== null && $key !== null) {
            foreach ($locales as $locale) {
                Cache::forget(SiteTranslation::cacheKey($category, $key, $locale));
            }

            return;
        }

        SiteTranslation::query()
            ->when($category !== null, fn ($query) => $query->where('category', $category))
            ->select(['category', 'key'])
            ->get()
            ->each(function (SiteTranslation $row) use ($locales): void {
                foreach ($locales as $locale) {
                    Cache::forget(SiteTranslation::cacheKey($row->category, $row->key, $locale));
                }
            });
    }
}
