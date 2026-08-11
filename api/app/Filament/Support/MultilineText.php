<?php

namespace App\Filament\Support;

use Filament\Forms\Components\Textarea;

class MultilineText
{
    /**
     * Plain text whose line breaks become <br> on the site, for headings the
     * design wraps by hand.
     */
    public static function make(string $field): Textarea
    {
        return Textarea::make($field)
            ->helperText(__('app.helper.line_breaks'))
            ->rows(2)
            ->autosize();
    }
}
