<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SiteTranslation extends Model
{
    use HasTranslations;

    protected $table = 'site_translations';

    protected $fillable = ['category', 'key', 'value', 'is_published'];

    public $translatable = ['value'];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}
