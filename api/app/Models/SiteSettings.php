<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSettings extends Model
{
    protected $table = 'site_settings';

    protected $fillable = ['name', 'value', 'is_published'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public static function get(string $name): ?string
    {
        return static::where('name', $name)->value('value');
    }

    public static function getValue(string $name, bool $onlyPublished = true): ?string
    {
        $query = static::query()->where('name', $name);

        if ($onlyPublished) {
            $query->where('is_published', true);
        }

        return $query->value('value');
    }

    public static function getEmbedUrl(string $name): ?string
    {
        $url = static::getValue($name);

        if (!$url) {
            return null;
        }

        return static::normalizeEmbedUrl($url);
    }

    public static function normalizeEmbedUrl(string $url): string
    {
        if (str_contains($url, '/embed/')) {
            return $url;
        }

        if (preg_match('/(?:youtu\.be\/|youtube\.com\/watch\?v=|v=)([^&\n?#]+)/', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        return $url;
    }
}
