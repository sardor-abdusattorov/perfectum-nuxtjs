<?php

namespace App\Models;

use App\Enums\Network;
use App\Models\Concerns\BelongsToNetwork;
use App\Models\Concerns\IsTaxonomy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class TariffType extends Model
{
    use BelongsToNetwork;
    use HasTranslations;
    use IsTaxonomy;

    protected $table = 'tariff_types';

    protected $fillable = ['category_id', 'name', 'slug', 'network', 'sort', 'status'];

    public $translatable = ['name'];

    protected $casts = [
        'network' => Network::class,
        'status' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(TariffCategory::class, 'category_id');
    }
}
