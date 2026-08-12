<?php

namespace App\Models;

use App\Enums\Network;
use App\Models\Concerns\BelongsToNetwork;
use App\Models\Concerns\CleansUpAttachedFiles;
use App\Models\Concerns\HasCategory;
use App\Models\Concerns\HasMediaUrl;
use App\Models\Concerns\Publishable;
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
        'image',
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

    public static function categoryModel(): string
    {
        return NewsCategory::class;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
