<?php

namespace App\Models;

use App\Enums\MenuLocation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Menu extends Model
{
    use HasTranslations;

    public const CACHE_TTL = 86400;

    protected $table = 'menus';

    protected $fillable = [
        'parent_id',
        'location',
        'column_position',
        'name',
        'url',
        'open_in_new_tab',
        'sort',
        'status',
    ];

    public $translatable = ['name', 'url'];

    protected $casts = [
        'location' => MenuLocation::class,
        'open_in_new_tab' => 'boolean',
        'status' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    public function scopeLocation(Builder $query, MenuLocation $location): Builder
    {
        return $query->where('location', $location);
    }

    /**
     * Top level items of a menu with their published children, ready to render.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, self>
     */
    public static function tree(MenuLocation $location): \Illuminate\Database\Eloquent\Collection
    {
        return static::query()
            ->published()
            ->location($location)
            ->whereNull('parent_id')
            ->with(['children' => fn (HasMany $query) => $query->where('status', true)])
            ->orderBy('sort')
            ->get();
    }

    public static function cacheKey(MenuLocation $location, string $locale): string
    {
        return "menus.{$location->value}.{$locale}";
    }
}
