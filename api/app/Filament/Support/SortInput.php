<?php

namespace App\Filament\Support;

use Filament\Forms\Components\TextInput;

class SortInput
{
    public static function make(string $field = 'sort'): TextInput
    {
        return TextInput::make($field)
            ->label(__('app.label.sort'))
            ->helperText(__('app.helper.sort'))
            ->numeric()
            ->default(0)
            ->required();
    }
}
