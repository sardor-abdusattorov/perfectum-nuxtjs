<?php

namespace App\Models;

use App\Models\Concerns\HasCategory;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Device extends Model
{
    use HasCategory;
    use HasTranslations;
    use Publishable;

    protected $table = 'devices';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'brand',
        'excerpt',
        'content',
        'specs',
        'image',
        'price',
        'in_stock',
        'sort',
        'status',
    ];

    public $translatable = ['name', 'excerpt', 'content'];

    protected $casts = [
        'specs' => 'array',
        'in_stock' => 'boolean',
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
