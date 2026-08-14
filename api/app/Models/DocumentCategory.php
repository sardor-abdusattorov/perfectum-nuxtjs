<?php

namespace App\Models;

use App\Models\Concerns\IsTaxonomy;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class DocumentCategory extends Model
{
    use HasTranslations;
    use IsTaxonomy;

    protected $table = 'document_categories';

    protected $fillable = [
        'name',
        'sort',
        'status',
    ];

    public $translatable = ['name'];

    protected $casts = [
        'status' => 'boolean',
    ];
}
