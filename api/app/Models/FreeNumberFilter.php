<?php

namespace App\Models;

use App\Models\Concerns\Publishable;
use Database\Factories\FreeNumberFilterFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreeNumberFilter extends Model
{
    /**
     * @use HasFactory<FreeNumberFilterFactory>
     */
    use HasFactory;

    use Publishable;

    public const TYPE_PREFIX = 'prefix';

    public const TYPE_NUMBER = 'number';

    public const TYPE_PRICE = 'price';

    public const TYPES = [self::TYPE_PREFIX, self::TYPE_NUMBER, self::TYPE_PRICE];

    protected $fillable = [
        'type',
        'name',
        'value',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * @return array<string, string>
     */
    public static function getTypeOptions(): array
    {
        return collect(self::TYPES)
            ->mapWithKeys(fn (string $type): array => [$type => __("app.filter_type.{$type}")])
            ->all();
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }
}
