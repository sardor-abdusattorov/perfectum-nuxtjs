<?php

namespace App\Models;

use App\Models\Concerns\HasCategory;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Document extends Model
{
    use HasCategory;
    use HasTranslations;
    use Publishable;

    protected $table = 'documents';

    protected $fillable = [
        'category_id',
        'name',
        'sort',
        'status',
    ];

    public $translatable = ['name'];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * The foreign key cascades in the database, which never reaches the model
     * events, so the rows are removed here to take their uploads with them.
     */
    protected static function booted(): void
    {
        static::deleting(function (self $document): void {
            $document->files->each->delete();
        });
    }

    public static function categoryModel(): string
    {
        return DocumentCategory::class;
    }

    public function files(): HasMany
    {
        return $this->hasMany(DocumentFile::class)->orderBy('sort')->orderBy('id');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('id');
    }

    /**
     * A file left without a language stands in for every locale, and a locale
     * with nothing of its own is handed the default one.
     */
    public function fileFor(?string $locale = null): ?DocumentFile
    {
        $locale ??= app()->getLocale();

        return $this->files->firstWhere('language', $locale)
            ?? $this->files->first(fn (DocumentFile $file): bool => blank($file->language))
            ?? $this->files->firstWhere('language', config('app.fallback_locale'));
    }
}
