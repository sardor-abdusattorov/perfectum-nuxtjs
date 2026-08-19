<?php

namespace App\Models;

use App\Enums\Network;
use App\Models\Concerns\BelongsToNetwork;
use App\Models\Concerns\IsTaxonomy;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TariffCategory extends Model
{
    use BelongsToNetwork;
    use HasTranslations;
    use IsTaxonomy;

    protected $table = 'tariff_categories';

    protected $fillable = ['name', 'network', 'sort', 'status', 'in_catalog'];

    public $translatable = ['name'];

    protected $casts = [
        'network' => Network::class,
        'in_catalog' => 'boolean',
        'status' => 'boolean',
    ];
}
