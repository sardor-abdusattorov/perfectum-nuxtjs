<?php

namespace App\Models;

use App\Enums\PageKey;
use App\Models\Concerns\HasMediaUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Translatable\HasTranslations;

class PageSettings extends Model
{
    use HasMediaUrl;
    use HasTranslations;

    public const CACHE_TTL = 86400;

    protected $table = 'page_settings';

    protected $fillable = ['key', 'meta_title', 'meta_description', 'meta_keywords', 'og_image', 'is_indexed'];

    public $translatable = ['meta_title', 'meta_description', 'meta_keywords'];

    protected $casts = [
        'key' => PageKey::class,
        'is_indexed' => 'boolean',
    ];

    public static function cacheKey(string $locale): string
    {
        return "page_settings.{$locale}";
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function map(?string $locale = null): array
    {
        $locale ??= app()->getLocale();

        return Cache::remember(
            static::cacheKey($locale),
            static::CACHE_TTL,
            fn (): array => static::query()
                ->get()
                ->mapWithKeys(fn (self $row): array => [$row->key->value => [
                    'title' => $row->meta_title,
                    'description' => $row->meta_description,
                    'keywords' => $row->meta_keywords,
                    'og_image' => $row->mediaUrl('og_image'),
                    'indexed' => $row->is_indexed,
                ]])
                ->all(),
        );
    }
}
