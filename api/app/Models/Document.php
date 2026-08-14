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

    /** @var array<int, string> */
    protected array $attachedFileFields = ['file'];

    protected $fillable = [
        'category_id',
        'name',
        'file',
        'size',
        'sort',
        'status',
    ];

    public $translatable = ['name'];

    protected $casts = [
        'size' => 'integer',
        'status' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $document): void {
            if ($document->isDirty('file')) {
                $document->size = filled($document->file) && Storage::disk('public')->exists($document->file)
                    ? Storage::disk('public')->size($document->file)
                    : null;
            }
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

    public function url(): ?string
    {
        return blank($this->file) ? null : Storage::disk('public')->url($this->file);
    }

    /**
     * The size is stamped on upload so the list never stats every file.
     */
    public function readableSize(): ?string
    {
        if (blank($this->size)) {
            return null;
        }

        return $this->size >= 1048576
            ? round($this->size / 1048576, 1).' '.__('app.unit.mb')
            : max(1, (int) round($this->size / 1024)).' '.__('app.unit.kb');
    }
}
