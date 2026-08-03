<?php

namespace App\Filament\Support;

use Filament\Forms\Components\FileUpload;

class MediaUpload
{
    /**
     * Audio/video upload field storing into media/{model}/{Y}/{m} on the
     * public disk.
     */
    public static function make(string $model, string $field = 'file'): FileUpload
    {
        return FileUpload::make($field)
            ->label(__('app.label.media'))
            ->disk('public')
            ->directory(fn () => "media/{$model}/".now()->format('Y/m'))
            ->visibility('public')
            ->acceptedFileTypes([
                'audio/*',
                'video/*',
            ])
            ->downloadable()
            ->previewable(false)
            ->nullable();
    }
}
