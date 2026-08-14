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

    /** @var array<int, string> */
    protected array $attachedFileFields = ['file'];

    protected $fillable = [
        'key',
        'name',
        'color',
        'file',
        'geojson',
        'sort',
        'status',
    ];

    public $translatable = ['name'];

    protected $casts = [
        'geojson' => 'array',
        'status' => 'boolean',
    ];

    /**
     * The archive is read once, when it is uploaded, so the site is handed
     * plain GeoJSON instead of unpacking a shapefile in every visitor's
     * browser.
     */
    protected static function booted(): void
    {
        static::saving(function (self $layer): void {
            if (! $layer->isDirty('file')) {
                return;
            }

            $layer->geojson = null;

            if (blank($layer->file) || ! Storage::disk('public')->exists($layer->file)) {
                return;
            }

            try {
                $layer->geojson = app(ShapefileReader::class)
                    ->fromZip(Storage::disk('public')->path($layer->file));
            } catch (Throwable $exception) {
                Log::warning("Coverage layer {$layer->key} could not be read: ".$exception->getMessage());
            }
        });
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('id');
    }

    public function featureCount(): int
    {
        return count($this->geojson['features'] ?? []);
    }
}
