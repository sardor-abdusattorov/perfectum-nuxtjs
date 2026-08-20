<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One partner's offer for one device: the terms it lends over and what the
 * visitor pays each month, exactly as the partner quotes them.
 */
class DeviceInstallment extends Model
{
    protected $table = 'device_installments';

    protected $fillable = ['device_id', 'installment_partner_id', 'options', 'sort'];

    protected $casts = [
        'options' => 'array',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(InstallmentPartner::class, 'installment_partner_id');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('id');
    }

    /**
     * A partner quotes either figure and the other follows from the term, so
     * the admin fills in what it was given and the site shows both.
     *
     * @return array<int, array{term: int, monthly: int, total: int}>
     */
    public function terms(): array
    {
        return collect($this->options ?? [])
            ->map(function (array $option): array {
                $term = max(1, (int) ($option['term'] ?? 1));
                $monthly = (int) ($option['monthly'] ?? 0);
                $total = (int) ($option['total'] ?? 0);

                return [
                    'term' => $term,
                    'monthly' => $monthly ?: (int) round($total / $term),
                    'total' => $total ?: $monthly * $term,
                ];
            })
            ->filter(fn (array $option): bool => $option['monthly'] > 0)
            ->sortBy('term')
            ->values()
            ->all();
    }
}
