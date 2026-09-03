<?php

namespace App\Models;

use App\Models\Concerns\CleansUpAttachedFiles;
use App\Models\Concerns\HasMediaUrl;
use App\Models\Concerns\IsTaxonomy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstallmentPartner extends Model
{
    use CleansUpAttachedFiles;
    use HasMediaUrl;
    use IsTaxonomy;

    protected $table = 'installment_partners';

    protected $fillable = ['name', 'slug', 'logo', 'url', 'sort', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * @var array<int, string>
     */
    public array $attachedFileFields = ['logo'];

    public function installments(): HasMany
    {
        return $this->hasMany(DeviceInstallment::class);
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
