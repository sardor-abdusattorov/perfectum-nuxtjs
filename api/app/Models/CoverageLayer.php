<?php

namespace App\Models;

use App\Models\Concerns\CleansUpAttachedFiles;
use App\Models\Concerns\Publishable;
use App\Services\Geo\ShapefileReader;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;
use Throwable;

class CoverageLayer extends Model
{
    use CleansUpAttachedFiles;
    use HasTranslations;
    use Publishable;

    protected $table = 'coverage_layers';

    /**
     * @var array<int, string>
     */
    protected array $attachedFileFields = ['file'];

    protected $fillable = [
        'key',
        'name',
        'color',
        'file',
        'geojson',
        'features',
        'sort',
        'status',
    ];

    public $translatable = ['name'];

    protected $casts = [
        'geojson' => 'array',
        'features' => 'integer',
        'status' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $layer): void {
            if ($layer->isDirty('file')) {
                $layer->geojson = self::read($layer);
            }

            if ($layer->isDirty('geojson')) {
                $layer->features = count($layer->geojson['features'] ?? []);
            }
        });
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('id');
    }

    /**
     * Reads the archive again and stores what comes out. The shapes are written
     * once, on upload, so a layer loaded before the reader changed keeps the old
     * numbers until something asks for them to be read anew.
     */
    public function refreshShapes(): bool
    {
        $geojson = self::read($this);

        if ($geojson === null) {
            return false;
        }

        $this->geojson = $geojson;

        return $this->save();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function featureCollection(): ?array
    {
        if ($this->geojson === null) {
            return null;
        }

        return [
            ...$this->geojson,
            'features' => array_map(
                fn (array $feature): array => [...$feature, 'properties' => (object) ($feature['properties'] ?? [])],
                $this->geojson['features'] ?? [],
            ),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    private static function read(self $layer): ?array
    {
        if (blank($layer->file) || ! Storage::disk('public')->exists($layer->file)) {
            return null;
        }

        try {
            return app(ShapefileReader::class)->fromZip(Storage::disk('public')->path($layer->file));
        } catch (Throwable $exception) {
            Log::warning("Coverage layer {$layer->key} could not be read: ".$exception->getMessage());

            return null;
        }
    }
}
