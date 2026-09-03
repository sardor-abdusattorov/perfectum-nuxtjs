<?php

namespace App\Models;

use App\Models\Concerns\CleansUpAttachedFiles;
use App\Models\Concerns\CountsViews;
use App\Models\Concerns\HasCategory;
use App\Models\Concerns\HasMediaUrl;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Device extends Model
{
    use CleansUpAttachedFiles;
    use CountsViews;
    use HasCategory;
    use HasMediaUrl;
    use HasTranslations;
    use Publishable;

    protected $table = 'devices';

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
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
        'views' => 'integer',
        'specs' => 'array',
        'in_stock' => 'boolean',
        'status' => 'boolean',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(DeviceBrand::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(DeviceInstallment::class);
    }

    public static function categoryModel(): string
    {
        return DeviceCategory::class;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
