<?php

namespace App\Models;

use App\Models\Concerns\HasCategory;
use App\Models\Concerns\HasMediaUrl;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Action extends Model
{
    use HasCategory;
    use HasMediaUrl;
    use HasTranslations;
    use Publishable;

    protected $table = 'actions';

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'badge',
        'excerpt',
        'content',
        'image',
        'starts_at',
        'ends_at',
        'status',
    ];

    public $translatable = ['title', 'badge', 'excerpt', 'content'];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
        'status' => 'boolean',
    ];

    public static function categoryModel(): string
    {
        return ActionCategory::class;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
