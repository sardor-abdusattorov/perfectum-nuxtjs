<?php

namespace App\Models;

use App\Models\Concerns\HasCategory;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Action extends Model
{
    use HasCategory;
    use HasTranslations;
    use Publishable;

    protected $table = 'actions';

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'badge',
        'excerpt',
        'content',
        'image',
        'starts_at',
        'ends_at',
        'status',
    ];

    public $translatable = ['title', 'badge', 'excerpt', 'content'];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
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
