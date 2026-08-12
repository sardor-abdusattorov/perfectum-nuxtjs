<?php

namespace App\Models;

use App\Enums\Network;
use App\Models\Concerns\BelongsToNetwork;
use App\Models\Concerns\IsTaxonomy;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class FaqCategory extends Model
{
    use BelongsToNetwork;
    use HasTranslations;
    use IsTaxonomy;

    protected $table = 'faq_categories';

    protected $fillable = ['name', 'slug', 'network', 'sort', 'status'];

    public $translatable = ['name'];

    protected $casts = [
        'network' => Network::class,
        'status' => 'boolean',
    ];
}
