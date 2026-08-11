<?php

namespace App\Models;

use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Vacancy extends Model
{
    use HasTranslations;
    use Publishable;

    protected $table = 'vacancies';

    protected $fillable = [
        'title',
        'slug',
        'city',
        'employment',
        'salary',
        'content',
        'sort',
        'status',
    ];

    public $translatable = ['title', 'city', 'employment', 'salary', 'content'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
