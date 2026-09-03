<?php

namespace App\Models;

use App\Enums\Network;
use App\Models\Concerns\BelongsToNetwork;
use App\Models\Concerns\CleansUpAttachedFiles;
use App\Models\Concerns\HasCategory;
use App\Models\Concerns\HasMediaUrl;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class News extends Model
{
    use BelongsToNetwork;
    use CleansUpAttachedFiles;
    use HasCategory {
        BelongsToNetwork::scopeForNetwork insteadof HasCategory;
    }
    use HasMediaUrl;
    use HasTranslations;
    use Publishable;

    protected $table = 'news';

    protected $fillable = [
        'category_id',
        'network',
        'title',
        'slug',
        'excerpt',
        'content',
        'preview_image',
        'main_image',
        'is_featured',
        'published_at',
        'status',
    ];

    public $translatable = ['title', 'excerpt', 'content'];

    protected $casts = [
        'network' => Network::class,
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'status' => 'boolean',
    ];

    /**
     * @var array<int, string>
     */
    public array $attachedFileFields = ['preview_image', 'main_image'];

    protected static function booted(): void
    {
        static::saved(function (self $news): void {
            if ($news->is_featured) {
                static::query()
                    ->whereKeyNot($news->getKey())
                    ->where('is_featured', true)
                    ->update(['is_featured' => false]);
            }
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public static function categoryModel(): string
    {
        return NewsCategory::class;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
