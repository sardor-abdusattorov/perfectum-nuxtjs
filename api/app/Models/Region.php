<?php

namespace App\Models;

use App\Enums\Network;
use App\Models\Concerns\BelongsToNetwork;
use App\Models\Concerns\IsTaxonomy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Region extends Model
{
    use BelongsToNetwork;
    use HasTranslations;
    use IsTaxonomy;

    protected $table = 'regions';

    protected $fillable = ['name', 'network', 'latitude', 'longitude', 'sort', 'status'];

    public $translatable = ['name'];

    protected $casts = [
        'network' => Network::class,
        'latitude' => 'float',
        'longitude' => 'float',
        'status' => 'boolean',
    ];

    /**
     * A region the coverage map can fly to is one that knows where it is.
     */
    public function scopeLocated(Builder $query): Builder
    {
        return $query->whereNotNull('latitude')->whereNotNull('longitude');
    }

    public function offices(): HasMany
    {
        return $this->hasMany(Office::class);
    }
}
