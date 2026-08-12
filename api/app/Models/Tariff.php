<?php

namespace App\Models;

use App\Enums\Network;
use App\Models\Concerns\CleansUpAttachedFiles;
use App\Models\Concerns\HasCategory;
use App\Models\Concerns\HasMediaUrl;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Builder;
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

    /** @var array<int, string> */
    protected array $attachedFileFields = ['image', 'modal_image'];

    protected $fillable = [
        'category_id',
        'type_id',
        'name',
        'slug',
        'price',
        'price_currency',
        'price_period',
        'connection_cost',
        'features',
        'descriptions',
        'image',
        'modal_image',
        'ussd',
        'buttons',
        'is_featured',
        'is_archived',
        'sort',
        'status',
    ];

    public $translatable = ['name', 'price_currency', 'price_period', 'connection_cost'];

    protected $casts = [
        'features' => 'array',
        'descriptions' => 'array',
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

    /**
     * A tariff belongs to the section it sits in and to the chip above it, so
     * either one may pin it to a network.
     */
    public function scopeForNetwork(Builder $query, ?Network $network): Builder
    {
        if ($network === null) {
            return $query;
        }

        return $query
            ->where(fn (Builder $builder) => $builder
                ->whereNull('category_id')
                ->orWhereHas('category', fn (Builder $category) => $category->forNetwork($network)))
            ->where(fn (Builder $builder) => $builder
                ->whereNull('type_id')
                ->orWhereHas('type', fn (Builder $type) => $type->forNetwork($network)));
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
