<?php

namespace App\Models;

use App\Models\Concerns\HasCategory;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Tariff extends Model
{
    use HasCategory;
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

    public function type(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'type_id');
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

    public function imageUrl(): ?string
    {
        return blank($this->image) ? null : Storage::disk('public')->url($this->image);
    }

    public function modalImageUrl(): ?string
    {
        return blank($this->modal_image) ? null : Storage::disk('public')->url($this->modal_image);
    }
}
