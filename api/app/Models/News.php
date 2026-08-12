<?php

namespace App\Models;

use App\Models\Concerns\HasCategory;
use App\Models\Concerns\HasMediaUrl;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class News extends Model
{
    use HasCategory;
    use HasMediaUrl;
    use HasTranslations;
    use Publishable;

    protected $table = 'news';

    protected $fillable = [
        'category_id',
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
