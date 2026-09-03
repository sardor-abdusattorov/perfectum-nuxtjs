<?php

namespace App\Models;

use App\Enums\Network;
use App\Models\Concerns\BelongsToNetwork;
use App\Models\Concerns\CleansUpAttachedFiles;
use App\Models\Concerns\CountsViews;
use App\Models\Concerns\HasCategory;
use App\Models\Concerns\HasMediaUrl;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Service extends Model
{
    use BelongsToNetwork, HasCategory {
        BelongsToNetwork::scopeForNetwork insteadof HasCategory;
    }
    use CleansUpAttachedFiles;
    use CountsViews;
    use HasMediaUrl;
    use HasTranslations;
    use Publishable;

    protected $table = 'services';

    protected $fillable = [
        'category_id',
        'network',
        'name',
        'slug',
        'excerpt',
        'lead',
        'content',
        'price',
        'icon',
        'image',
        'ussd',
        'facts',
        'steps',
        'is_featured',
        'sort',
        'status',
    ];

    public $translatable = ['name', 'excerpt', 'lead', 'content', 'price'];

    protected $casts = [
        'views' => 'integer',
        'network' => Network::class,
        'facts' => 'array',
        'steps' => 'array',
        'is_featured' => 'boolean',
        'status' => 'boolean',
    ];

    public static function categoryModel(): string
    {
        return ServiceCategory::class;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
