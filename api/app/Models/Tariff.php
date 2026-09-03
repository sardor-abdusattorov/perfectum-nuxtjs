<?php

namespace App\Models;

use App\Models\Concerns\CleansUpAttachedFiles;
use App\Models\Concerns\HasCategory;
use App\Models\Concerns\HasMediaUrl;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Tariff extends Model
{
    use CleansUpAttachedFiles;
    use HasCategory;
    use HasMediaUrl;
    use HasTranslations;
    use Publishable;

    protected $table = 'tariffs';

    /**
     * @var array<int, string>
     */
    protected array $attachedFileFields = ['image', 'modal_image'];

    protected $fillable = [
        'category_id',
        'type_id',
        'name',
        'slug',
        'price',
        'price_currency',
        'price_period',
        'features',
        'descriptions',
        'image',
        'modal_image',
        'buttons',
        'sort',
        'status',
    ];

    public $translatable = ['name', 'price_currency', 'price_period'];

    protected $casts = [
        'features' => 'array',
        'descriptions' => 'array',
        'buttons' => 'array',
        'status' => 'boolean',
    ];

    public static function categoryModel(): string
    {
        return TariffCategory::class;
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(TariffType::class, 'type_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function modalImageUrl(): ?string
    {
        return $this->mediaUrl('modal_image');
    }
}
