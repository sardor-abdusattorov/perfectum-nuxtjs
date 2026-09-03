<?php

namespace App\Models;

use App\Models\Concerns\CleansUpAttachedFiles;
use App\Models\Concerns\HasCategory;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Document extends Model
{
    use CleansUpAttachedFiles;
    use HasCategory;
    use HasTranslations;
    use Publishable;

    protected $table = 'documents';

    /**
     * @var array<int, string>
     */
    protected array $attachedFileFields = ['file'];

    protected $fillable = [
        'category_id',
        'name',
        'file',
        'size',
        'is_downloadable',
        'sort',
        'status',
    ];

    public $translatable = ['name', 'file', 'size'];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = ['is_downloadable' => true];

    protected $casts = [
        'is_downloadable' => 'boolean',
        'status' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $document): void {
            if (! $document->isDirty('file')) {
                return;
            }

            $sizes = [];

            foreach ($document->getTranslations('file') as $locale => $path) {
                if (filled($path) && Storage::disk('public')->exists($path)) {
                    $sizes[$locale] = Storage::disk('public')->size($path);
                }
            }

            $document->setTranslations('size', $sizes);
        });
    }

    public static function categoryModel(): string
    {
        return DocumentCategory::class;
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('id');
    }

    public function url(?string $locale = null): ?string
    {
        return stored_url($this->translated('file', $locale));
    }

    public function readableSize(?string $locale = null): ?string
    {
        $size = (int) $this->translated('size', $locale);

        if ($size <= 0) {
            return null;
        }

        return $size >= 1048576
            ? round($size / 1048576, 1).' '.__('app.unit.mb')
            : max(1, (int) round($size / 1024)).' '.__('app.unit.kb');
    }

    private function translated(string $field, ?string $locale): mixed
    {
        $translations = $this->getTranslations($field);
        $locale ??= app()->getLocale();

        return $translations[$locale]
            ?? $translations[config('app.fallback_locale')]
            ?? collect($translations)->first(fn (mixed $value): bool => filled($value));
    }
}
