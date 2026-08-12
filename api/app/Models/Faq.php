<?php

namespace App\Models;

use App\Models\Concerns\HasCategory;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Faq extends Model
{
    use HasCategory;
    use HasTranslations;
    use Publishable;

    protected $table = 'faqs';

    protected $fillable = [
        'category_id',
        'question',
        'answer',
        'sort',
        'status',
    ];

    public $translatable = ['question', 'answer'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public static function categoryModel(): string
    {
        return FaqCategory::class;
    }
}
