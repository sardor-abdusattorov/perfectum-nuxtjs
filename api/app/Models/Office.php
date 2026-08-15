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
        'dealers_count',
        'content',
        'lat',
        'lng',
        'sort',
        'status',
    ];

    public $translatable = ['district', 'address', 'content'];

    protected $casts = [
        'type' => OfficeType::class,
        'network' => Network::class,
        'dealers_count' => 'integer',
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

    public function scopeInRegion(Builder $query, mixed $region): Builder
    {
        if (blank($region)) {
            return $query;
        }

        return $query->where('region_id', $region);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('id');
    }
}
