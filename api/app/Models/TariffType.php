<?php

namespace App\Models;

use App\Models\Concerns\IsTaxonomy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class TariffType extends Model
{
    use HasTranslations;
    use IsTaxonomy;

    protected $table = 'tariff_types';

    protected $fillable = ['category_id', 'name', 'slug', 'sort', 'status'];

    public $translatable = ['name'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(TariffCategory::class, 'category_id');
    }
}
