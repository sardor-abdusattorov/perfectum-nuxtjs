<?php

namespace App\Filament\Support;

use Filament\Forms\Components\FileUpload;

class MediaUpload
{
    public static function make(string $model, string $field = 'file'): FileUpload
    {
        return FileUpload::make($field)
            ->label('Медиа')
            ->disk('public')
            ->directory(fn () => "media/{$model}/" . now()->format('Y/m'))
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
