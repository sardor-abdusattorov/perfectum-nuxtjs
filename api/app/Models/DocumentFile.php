<?php

namespace App\Models;

use App\Models\Concerns\CleansUpAttachedFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class DocumentFile extends Model
{
    use CleansUpAttachedFiles;

    protected $table = 'document_files';

    /** @var array<int, string> */
    protected array $attachedFileFields = ['file'];

    protected $fillable = [
        'document_id',
        'language',
        'file',
        'size',
        'sort',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $file): void {
            if ($file->isDirty('file')) {
                $file->size = filled($file->file) && Storage::disk('public')->exists($file->file)
                    ? Storage::disk('public')->size($file->file)
                    : null;
            }
        });
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
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
