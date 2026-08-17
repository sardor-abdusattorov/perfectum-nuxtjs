<?php

namespace App\Models;

use App\Models\Concerns\CleansUpAttachedFiles;
use App\Models\Concerns\HasMediaUrl;
use App\Models\Concerns\IsTaxonomy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeviceBrand extends Model
{
    use CleansUpAttachedFiles;
    use HasMediaUrl;
    use IsTaxonomy;

    protected $table = 'device_brands';

    protected $fillable = ['name', 'slug', 'logo', 'color', 'sort', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];

    /** @var array<int, string> */
    public array $attachedFileFields = ['logo'];

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class, 'brand_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function logoUrl(): ?string
    {
        return $this->mediaUrl('logo');
    }
}
