<?php

namespace App\Models;

use App\Models\Concerns\CleansUpAttachedFiles;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TariffFile extends Model
{
    use CleansUpAttachedFiles;
    use Publishable;

    protected $table = 'tariff_files';

    /** @var array<int, string> */
    protected array $attachedFileFields = ['file'];

    protected $fillable = [
        'name',
        'file',
        'sort',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderByDesc('id');
    }
}
