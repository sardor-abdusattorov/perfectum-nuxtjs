<?php

namespace App\Filament\Support;

use Guava\IconPicker\Forms\Components\IconPicker as GuavaIconPicker;

class IconPicker
{
    /**
     * Icon field storing a Blade Icons name. Kept behind this class so the
     * whole panel switches pickers from one place.
     */
    public static function make(string $field = 'icon'): GuavaIconPicker
    {
        return GuavaIconPicker::make($field)
            ->label(__('app.label.icon'))
            ->helperText(__('app.helper.icon'))
            ->required();
    }
}
