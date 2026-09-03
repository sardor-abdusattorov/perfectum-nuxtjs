<?php

namespace App\Models;

use App\Models\Concerns\CountsViews;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Vacancy extends Model
{
    use CountsViews;
    use HasTranslations;
    use Publishable;

    protected $table = 'vacancies';

    protected $fillable = [
        'title',
        'slug',
        'department',
        'city',
        'employment',
        'salary',
        'content',
        'sort',
        'status',
    ];

    public $translatable = ['title', 'department', 'city', 'employment', 'salary', 'content'];

    protected $casts = [
        'views' => 'integer',
        'status' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
