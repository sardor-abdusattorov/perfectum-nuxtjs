<?php

namespace App\Models;

use Database\Factories\ApplicationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    /** @use HasFactory<ApplicationFactory> */
    use HasFactory;

    public const STATUS_NEW = 'new';

    public const STATUS_PROCESSED = 'processed';

    public const STATUSES = [self::STATUS_NEW, self::STATUS_PROCESSED];

    public const THEME_CONNECTION = 'connection';

    public const THEME_TARIFFS = 'tariffs';

    public const THEME_SUPPORT = 'support';

    public const THEME_OTHER = 'other';

    public const THEMES = [
        self::THEME_CONNECTION,
        self::THEME_TARIFFS,
        self::THEME_SUPPORT,
        self::THEME_OTHER,
    ];

    protected $fillable = [
        'name',
        'phone',
        'email',
        'theme',
        'message',
        'status',
        'ip_address',
    ];

    /**
     * @return array<string, string>
     */
    public static function getStatusOptions(): array
    {
        return collect(self::STATUSES)
            ->mapWithKeys(fn (string $status): array => [$status => __("app.application_status.{$status}")])
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public static function getThemeOptions(): array
    {
        return collect(self::THEMES)
            ->mapWithKeys(fn (string $theme): array => [$theme => __("app.application_theme.{$theme}")])
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

    public static function themeLabel(?string $theme): string
    {
        return self::getThemeOptions()[$theme] ?? (string) $theme;
    }
}