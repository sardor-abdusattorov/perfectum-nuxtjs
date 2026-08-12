<?php

namespace App\Models;

use App\Models\Concerns\HasCategory;
use App\Models\Concerns\HasMediaUrl;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Tariff extends Model
{
    use HasCategory;
    use HasMediaUrl;
    use HasTranslations;
    use Publishable;

    protected $table = 'tariffs';

    protected $fillable = [
        'category_id',
        'type_id',
        'name',
        'slug',
        'price',
        'price_currency',
        'price_period',
        'lead',
        'features',
        'terms',
        'image',
        'modal_image',
        'ussd',
        'buttons',
        'is_featured',
        'is_archived',
        'sort',
        'status',
    ];

    public $translatable = ['name', 'price_currency', 'price_period', 'lead', 'terms'];

    protected $casts = [
        'features' => 'array',
        'buttons' => 'array',
        'is_featured' => 'boolean',
        'is_archived' => 'boolean',
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

    public function scopeCurrent(Builder $query): Builder
    {
        return $query->where('is_archived', false);
    }

    public function scopeArchived(Builder $query): Builder
    {
        return $query->where('is_archived', true);
    }

    public function scopeOfType(Builder $query, ?string $slug): Builder
    {
        if (blank($slug)) {
            return $query;
        }

        return $query->whereHas('type', fn (Builder $type) => $type->where('slug', $slug));
    }

    public function modalImageUrl(): ?string
    {
        return $this->mediaUrl('modal_image');
    }
}
