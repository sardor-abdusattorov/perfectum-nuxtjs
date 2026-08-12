<?php

namespace App\Models;

use App\Enums\Network;
use App\Enums\OfficeType;
use App\Models\Concerns\BelongsToNetwork;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Office extends Model
{
    use BelongsToNetwork;
    use HasTranslations;
    use Publishable;

    protected $table = 'offices';

    protected $fillable = [
        'type',
        'region_id',
        'network',
        'name',
        'district',
        'address',
        'phone',
        'lat',
        'lng',
        'sort',
        'status',
    ];

    public $translatable = ['district', 'address'];

    protected $casts = [
        'type' => OfficeType::class,
        'network' => Network::class,
        'lat' => 'float',
        'lng' => 'float',
        'status' => 'boolean',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function scopeOfType(Builder $query, ?OfficeType $type): Builder
    {
        return $type === null ? $query : $query->where('type', $type);
    }

    public function scopeInRegion(Builder $query, ?string $slug): Builder
    {
        if (blank($slug)) {
            return $query;
        }

        return $query->whereHas('region', fn (Builder $region) => $region->where('slug', $slug));
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('id');
    }
}
