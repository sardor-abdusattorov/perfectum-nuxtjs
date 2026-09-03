<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationNote extends Model
{
    protected $table = 'application_notes';

    protected $fillable = ['application_id', 'user_id', 'author_name', 'body'];

    /**
     * The name is copied in beside the link. An account can be deleted long
     * after the note was written, and «кто это писал» is the first thing anyone
     * asks when reading the journal a month later.
     */
    protected static function booted(): void
    {
        static::creating(function (self $note): void {
            $note->user_id ??= auth()->id();
            $note->author_name ??= auth()->user()?->name;
        });
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeNewestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('created_at')->orderByDesc('id');
    }

    public function authorLabel(): string
    {
        return $this->author?->name
            ?? $this->author_name
            ?? __('app.label.author_unknown');
    }
}
