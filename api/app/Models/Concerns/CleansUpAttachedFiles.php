<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

trait CleansUpAttachedFiles
{
    public static function bootCleansUpAttachedFiles(): void
    {
        static::deleting(function (Model $model): void {
            $fields = property_exists($model, 'attachedFileFields')
                ? $model->attachedFileFields
                : ['image'];

            foreach ($fields as $field) {
                foreach ((array) $model->{$field} as $path) {
                    if (is_string($path) && filled($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }
        });
    }
}
