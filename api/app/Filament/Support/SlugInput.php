<?php

namespace App\Filament\Support;

use App\Support\Slug;
use Closure;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class SlugInput
{
    public static function make(string $field = 'slug'): TextInput
    {
        return TextInput::make($field)
            ->label(__('app.label.slug'))
            ->helperText(__('app.helper.slug'))
            ->unique(ignoreRecord: true)
            ->alphaDash()
            ->maxLength(255);
    }

    public static function preview(string $field = 'slug'): Closure
    {
        return function (Set $set, Get $get, ?string $state, string $operation) use ($field): void {
            if ($operation === 'create' && blank($get($field))) {
                $set($field, Slug::base($state ?? ''));
            }
        };
    }
}
