<?php

namespace App\Models;

use Database\Factories\ApplicationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    /** @use HasFactory<ApplicationFactory> */
    use HasFactory;

    public const STATUS_NEW = 'new';

    public const STATUS_PROCESSED = 'processed';

    public const STATUSES = [self::STATUS_NEW, self::STATUS_PROCESSED];

    protected $fillable = [
        'name',
        'phone',
        'email',
        'theme_id',
        'message',
        'status',
        'ip_address',
    ];

    public function theme(): BelongsTo
    {
        return $this->belongsTo(ApplicationTheme::class);
    }

    /**
     * @return array<string, string>
     */
    public static function getStatusOptions(): array
    {
        return collect(self::STATUSES)
            ->mapWithKeys(fn (string $status): array => [$status => __("app.application_status.{$status}")])
            ->all();
    }

    public static function statusLabel(?string $status): string
    {
        return self::getStatusOptions()[$status] ?? (string) $status;
    }

    public static function statusColor(?string $status): string
    {
        return match ($status) {
            self::STATUS_NEW => 'danger',
            self::STATUS_PROCESSED => 'success',
            default => 'gray',
        };
    }
}
