<?php

namespace App\Models;

use App\Enums\CategoryType;
use App\Enums\Network;
use App\Models\Concerns\BelongsToNetwork;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use BelongsToNetwork;
    use HasTranslations;
    use Publishable;

    public const CACHE_TTL = 86400;

    protected $table = 'categories';

    protected $fillable = [
        'type',
        'network',
        'name',
        'slug',
        'sort',
        'status',
    ];

    public $translatable = ['name'];

    protected $casts = [
        'type' => CategoryType::class,
        'network' => Network::class,
        'status' => 'boolean',
    ];

    public function scopeType(Builder $query, CategoryType $type): Builder
    {
        return $query->where('type', $type);
    }

    public static function cacheKey(CategoryType $type, string $locale): string
    {
        return "categories.{$type->value}.{$locale}";
    }
}
