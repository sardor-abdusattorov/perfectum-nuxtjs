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

class Action extends Model
{
    use BelongsToNetwork;
    use CleansUpAttachedFiles;
    use CountsViews;
    use HasCategory {
        BelongsToNetwork::scopeForNetwork insteadof HasCategory;
    }
    use HasMediaUrl;
    use HasTranslations;
    use Publishable;

    protected $table = 'actions';

    protected $fillable = [
        'category_id',
        'network',
        'title',
        'slug',
        'badge',
        'excerpt',
        'content',
        'preview_image',
        'main_image',
        'starts_at',
        'ends_at',
        'status',
    ];

    public $translatable = ['title', 'badge', 'excerpt', 'content'];

    protected $casts = [
        'views' => 'integer',
        'network' => Network::class,
        'starts_at' => 'date',
        'ends_at' => 'date',
        'status' => 'boolean',
    ];

    /**
     * @var array<int, string>
     */
    public array $attachedFileFields = ['preview_image', 'main_image'];

    public static function categoryModel(): string
    {
        return ActionCategory::class;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
