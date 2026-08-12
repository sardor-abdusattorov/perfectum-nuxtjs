<?php

namespace App\Models;

use App\Models\Concerns\HasCategory;
use App\Models\Concerns\HasMediaUrl;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Device extends Model
{
    use HasCategory;
    use HasMediaUrl;
    use HasTranslations;
    use Publishable;

    protected $table = 'devices';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'brand',
        'excerpt',
        'content',
        'specs',
        'image',
        'price',
        'in_stock',
        'sort',
        'status',
    ];

    public $translatable = ['name', 'excerpt', 'content'];

    protected $casts = [
        'specs' => 'array',
        'in_stock' => 'boolean',
        'status' => 'boolean',
    ];

    public static function categoryModel(): string
    {
        return DeviceCategory::class;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
