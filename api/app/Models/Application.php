<?php

namespace App\Models;

use Database\Factories\ApplicationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Application extends Model
{
    /** @use HasFactory<ApplicationFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'theme_id',
        'message',
        'status_id',
        'processed_at',
        'ip_address',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    /**
     * The clock starts when the visitor sends the form and stops the first time
     * an operator moves the application off the status it arrives with — that
     * span is what the list reports as the handling time.
     */
    protected static function booted(): void
    {
        static::saving(function (self $application): void {
            if (! $application->isDirty('status_id') || $application->processed_at !== null) {
                return;
            }

            $status = ApplicationStatus::query()->find($application->status_id);

            if ($status !== null && ! $status->is_default) {
                $application->processed_at = now();
            }
        });
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(ApplicationTheme::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ApplicationStatus::class, 'status_id');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(ApplicationNote::class)->newestFirst();
    }

    public function addNote(string $body): ?ApplicationNote
    {
        return blank(trim($body)) ? null : $this->notes()->create(['body' => trim($body)]);
    }

    public function handlingSeconds(): ?int
    {
        return $this->processed_at === null || $this->created_at === null
            ? null
            : max(0, (int) $this->created_at->diffInSeconds($this->processed_at));
    }

    public static function readableHandlingTime(?int $seconds): ?string
    {
        if ($seconds === null) {
            return null;
        }

        if ($seconds < 60) {
            return __('app.duration.seconds', ['value' => $seconds]);
        }

        if ($seconds < 3600) {
            return __('app.duration.minutes', ['value' => intdiv($seconds, 60)]);
        }

        if ($seconds < 86400) {
            return __('app.duration.hours', ['value' => intdiv($seconds, 3600)])
                .' '.__('app.duration.minutes', ['value' => intdiv($seconds % 3600, 60)]);
        }

        return __('app.duration.days', ['value' => intdiv($seconds, 86400)])
            .' '.__('app.duration.hours', ['value' => intdiv($seconds % 86400, 3600)]);
    }
}
