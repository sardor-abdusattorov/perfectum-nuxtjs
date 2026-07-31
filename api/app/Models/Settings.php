<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Settings extends Model
{
    protected $table = 'settings';

    protected $fillable = ['key', 'value'];

    public function getValueAttribute($value)
    {
        if ($value === null) {
            return null;
        }

        $decoded = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        return $value;
    }

    public function setValueAttribute($value)
    {
        $this->attributes['value'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("settings.{$key}", 86400, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();

            return $setting ? $setting->value : $default;
        });
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function getOgImage(): ?string
    {
        $path = self::get('seo.og_image');

        if (blank($path)) {
            return null;
        }

        return str_starts_with($path, 'http')
            ? $path
            : asset(Storage::url($path));
    }

    public static function seo(): array
    {
        $locale = app()->getLocale();

        $titles = self::get('seo.title', []);
        $descriptions = self::get('seo.description', []);
        $keywords = self::get('seo.keywords', []);

        return [
            'title' => $titles[$locale] ?? config('app.name'),
            'description' => $descriptions[$locale] ?? '',
            'keywords' => $keywords[$locale] ?? '',
            'robots' => self::get('seo.indexing_enabled', true)
                ? 'index, follow'
                : 'noindex, nofollow',
            'ogImage' => self::getOgImage(),
        ];
    }
}
