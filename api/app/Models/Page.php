<?php

namespace App\Models;

use App\Models\Concerns\CleansUpAttachedFiles;
use App\Models\Concerns\CountsViews;
use App\Models\Concerns\HasMediaUrl;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use CleansUpAttachedFiles;
    use CountsViews;
    use HasMediaUrl;
    use HasTranslations;
    use Publishable;

    public const CACHE_TTL = 86400;

    protected $table = 'pages';

    protected $fillable = [
        'parent_id',
        'slug',
        'title',
        'content',
        'image',
        'meta_title',
        'meta_description',
        'redirect_from',
        'is_group',
        'sort',
        'status',
    ];

    public $translatable = ['title', 'content', 'meta_title', 'meta_description'];

    protected $casts = [
        'views' => 'integer',
        'redirect_from' => 'array',
        'sort' => 'integer',
        'is_group' => 'boolean',
        'status' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Unscoped on purpose: the admin picker attaches and detaches through this
     * relationship, and a filter here would hide an unpublished card from the
     * picker and leave it silently attached.
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort')->orderBy('id');
    }

    protected static function booted(): void
    {
        /**
         * A group is a heading and a list of cards, nothing else. The form
         * hides the rest, but hidden fields are simply not written — whatever
         * the page carried before the switch would stay in the table and come
         * back the day someone turns grouping off. So it is cleared here, on
         * every path into the row, and the picture goes off the disk with it.
         */
        static::saving(function (self $page): void {
            if ($page->is_group) {
                if (filled($page->image)) {
                    Storage::disk('public')->delete($page->image);
                }

                $page->forceFill([
                    'content' => null,
                    'image' => null,
                    'meta_title' => null,
                    'meta_description' => null,
                    'redirect_from' => null,
                ]);
            }
        });

        static::saving(function (self $page): void {
            if (! $page->isDirty('redirect_from')) {
                return;
            }

            $paths = collect($page->redirect_from ?? [])
                ->map(function (string $path): string {
                    $path = preg_replace('#^https?://[^/]+#i', '', trim($path)) ?? '';
                    $path = strtok($path, '?#') ?: '';
                    $path = trim($path, '/');

                    return preg_replace('#^(ru|uz)(/|$)#', '', $path) ?? '';
                })
                ->filter()
                ->unique()
                ->values()
                ->all();

            $page->redirect_from = $paths ?: null;
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function cacheKey(string $slug, string $locale): string
    {
        return "pages.{$slug}.{$locale}";
    }

    public static function redirectsCacheKey(): string
    {
        return 'pages.redirects';
    }
}
