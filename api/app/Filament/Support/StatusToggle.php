<?php

namespace App\Filament\Support;

use Filament\Forms\Components\Toggle;

class StatusToggle
{
    /**
     * Publish switch for a repeater item or a standalone block element.
     */
    public static function make(string $field = 'status'): Toggle
    {
        return Toggle::make($field)
            ->label(__('app.label.show_on_site'))
            ->helperText(__('app.helper.if_disabled_not_shown'))
            ->default(true);
    }
}
