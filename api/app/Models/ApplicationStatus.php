<?php

namespace App\Models;

use App\Models\Concerns\IsTaxonomy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Spatie\Translatable\HasTranslations;

class ApplicationStatus extends Model
{
    use HasTranslations;
    use IsTaxonomy;

    protected $table = 'application_statuses';

    protected $fillable = ['name', 'slug', 'color', 'is_default', 'sort', 'status'];

    public $translatable = ['name'];

    protected $casts = [
        'is_default' => 'boolean',
        'status' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function (self $status): void {
            if ($status->is_default) {
                static::query()
                    ->whereKeyNot($status->getKey())
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }
        });
    }

    /**
     * @return array<string, string>
     */
    public static function colorOptions(): array
    {
        return collect(['gray', 'danger', 'warning', 'success', 'info', 'primary'])
            ->mapWithKeys(fn (string $color): array => [$color => __("app.badge_color.{$color}")])
            ->all();
    }

    /**
     * A retired status still labels the applications that carry it, but it is
     * no longer offered when someone picks one.
     *
     * @return array<int, string>
     */
    public static function options(): array
    {
        return Cache::remember(
            static::cacheKey(app()->getLocale()),
            static::CACHE_TTL,
            fn (): array => static::query()
                ->published()
                ->ordered()
                ->get(['id', 'name'])
                ->mapWithKeys(fn (self $row): array => [$row->getKey() => $row->name])
                ->all(),
        );
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'status_id');
    }

    public static function default(): ?self
    {
        return static::query()->where('is_default', true)->first()
            ?? static::query()->ordered()->first();
    }

    public function isInUse(): bool
    {
        return $this->applications()->exists();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
