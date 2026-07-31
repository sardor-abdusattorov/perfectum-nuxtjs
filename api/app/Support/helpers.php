<?php

use App\Enums\PageKey;
use App\Models\ContentBlock;
use App\Models\Settings;
use App\Models\SiteSettings;
use App\Models\SiteTranslation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;


if (! function_exists('settings')) {
    function settings(string $key, mixed $default = null): mixed
    {
        return Cache::remember("settings.{$key}", 86400, function () use ($key, $default) {
            $setting = Settings::where('key', $key)->first();

            return $setting?->value ?? $default;
        });
    }
}

if (! function_exists('clear_settings_cache')) {
    function clear_settings_cache(?string $key = null): void
    {
        if ($key !== null) {
            Cache::forget("settings.{$key}");

            return;
        }

        Settings::query()->pluck('key')->each(
            fn (string $k) => Cache::forget("settings.{$k}")
        );
    }
}

if (! function_exists('site_setting')) {
    function site_setting(string $key, mixed $default = null): mixed
    {
        return Cache::remember("site_setting.{$key}", 86400, function () use ($key, $default) {
            $setting = SiteSettings::query()
                ->where('name', $key)
                ->where('is_published', true)
                ->first();

            return $setting?->value ?? $default;
        });
    }
}

if (! function_exists('clear_site_settings_cache')) {
    function clear_site_settings_cache(?string $key = null): void
    {
        if ($key !== null) {
            Cache::forget("site_setting.{$key}");

            return;
        }

        SiteSettings::query()->pluck('name')->each(
            fn (string $name) => Cache::forget("site_setting.{$name}")
        );
    }
}

if (! function_exists('translator')) {
    function translator(
        string $category,
        ?string $key = null,
        array $replace = [],
        ?string $locale = null
    ): string {
        $locale ??= app()->getLocale();

        if ($key === null && str_contains($category, '.')) {
            [$category, $key] = explode('.', $category, 2);
        }

        if ($key === null) {
            return $category;
        }

        $cacheKey = "translator.{$category}.{$key}.{$locale}";

        $value = Cache::remember($cacheKey, 86400, function () use ($category, $key, $locale) {
            $row = SiteTranslation::query()
                ->where('category', $category)
                ->where('key', $key)
                ->where('is_published', true)
                ->first();

            if ($row === null) {
                return null;
            }

            $translations = $row->getTranslations('value');

            return $translations[$locale]
                ?? $translations[config('app.fallback_locale')]
                ?? (reset($translations) ?: null);
        });

        if ($value === null) {
            return $key;
        }

        foreach ($replace as $k => $v) {
            $value = str_replace(':'.$k, (string) $v, (string) $value);
        }

        return (string) $value;
    }
}

if (! function_exists('clear_translator_cache')) {
    function clear_translator_cache(?string $category = null, ?string $key = null): void
    {
        $locales = array_keys(config('laravellocalization.supportedLocales', []));
        if (empty($locales)) {
            $locales = [config('app.locale', 'en')];
        }

        if ($category && $key) {
            foreach ($locales as $loc) {
                Cache::forget("translator.{$category}.{$key}.{$loc}");
            }

            return;
        }

        $query = SiteTranslation::query();

        if ($category) {
            $query->where('category', $category);
        }

        $query->select(['category', 'key'])
            ->get()
            ->each(function ($row) use ($locales) {
                foreach ($locales as $loc) {
                    Cache::forget("translator.{$row->category}.{$row->key}.{$loc}");
                }
            });
    }
}