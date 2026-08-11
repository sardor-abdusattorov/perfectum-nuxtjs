<?php

namespace App\Models;

use App\Models\Concerns\HasCategory;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Service extends Model
{
    use HasCategory;
    use HasTranslations;
    use Publishable;

    protected $table = 'services';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'excerpt',
        'lead',
        'content',
        'price',
        'icon',
        'image',
        'ussd',
        'facts',
        'steps',
        'is_featured',
        'sort',
        'status',
    ];

    public $translatable = ['name', 'excerpt', 'lead', 'content', 'price'];

    protected $casts = [
        'facts' => 'array',
        'steps' => 'array',
        'is_featured' => 'boolean',
        'status' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function imageUrl(): ?string
    {
        return blank($this->image) ? null : Storage::disk('public')->url($this->image);
    }
}
