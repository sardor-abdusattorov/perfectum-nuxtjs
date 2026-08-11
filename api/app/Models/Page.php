<?php

namespace App\Models;

use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasTranslations;
    use Publishable;

    public const CACHE_TTL = 86400;

    protected $table = 'pages';

    protected $fillable = [
        'slug',
        'title',
        'content',
        'image',
        'meta_title',
        'meta_description',
        'status',
    ];

    public $translatable = ['title', 'content', 'meta_title', 'meta_description'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function cacheKey(string $slug): string
    {
        return "pages.{$slug}";
    }

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        return Cache::remember(
            static::cacheKey((string) $value),
            static::CACHE_TTL,
            fn (): ?self => static::query()->published()->where('slug', $value)->first(),
        );
    }

    public function imageUrl(): ?string
    {
        if (blank($this->image)) {
            return null;
        }

        return str_starts_with($this->image, 'http')
            ? $this->image
            : Storage::disk('public')->url($this->image);
    }
}
