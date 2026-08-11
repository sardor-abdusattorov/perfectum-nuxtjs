<?php

namespace App\Filament\Support;

use Filament\Forms\Components\TextInput;

class SlugInput
{
    public static function make(string $field = 'slug'): TextInput
    {
        return TextInput::make($field)
            ->label(__('app.label.slug'))
            ->helperText(__('app.helper.slug'))
            ->unique(ignoreRecord: true)
            ->alphaDash();
    }
}
