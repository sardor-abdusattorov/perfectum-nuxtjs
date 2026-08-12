<?php

namespace App\Models;

use App\Enums\TenderState;
use App\Models\Concerns\CleansUpAttachedFiles;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Tender extends Model
{
    use CleansUpAttachedFiles;
    use HasTranslations;
    use Publishable;

    protected $table = 'tenders';

    /** @var array<int, string> */
    protected array $attachedFileFields = ['files'];

    protected $fillable = [
        'title',
        'slug',
        'content',
        'files',
        'state',
        'deadline_at',
        'status',
    ];

    public $translatable = ['title', 'content'];

    protected $casts = [
        'files' => 'array',
        'state' => TenderState::class,
        'deadline_at' => 'date',
        'status' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
