<?php

namespace App\Filament\Support;

use Filament\Forms\Components\FileUpload;

class ImageUpload
{
    /**
     * Standard image upload field for a model.
     *
     * Usage:
     *   ImageUpload::make('text-blocks')
     *   ImageUpload::make('banners')
     *   ImageUpload::make('products', field: 'thumbnail')
     *
     * Saves to: storage/app/public/uploads/{model}/2025/03/{uuid}.jpg
     * Public URL: /storage/uploads/{model}/2025/03/{uuid}.jpg
     */
    public static function make(string $model, string $field = 'image'): FileUpload
    {
        return FileUpload::make($field)
            ->label(__('app.label.image'))
            ->disk('public')
            ->directory(fn () => "uploads/{$model}/".now()->format('Y/m'))
            ->visibility('public')
            ->image()
            ->imageEditor()
            ->previewable()
            ->downloadable()
            ->maxSize(6144) // 6 MB — единый лимит для всех изображений в админке
            ->nullable();
    }
}
