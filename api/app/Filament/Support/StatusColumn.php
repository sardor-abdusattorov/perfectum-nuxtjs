<?php

namespace App\Filament\Support;

use Filament\Tables\Columns\ToggleColumn;

class StatusColumn
{
    public static function make(string $field = 'status'): ToggleColumn
    {
        return ToggleColumn::make($field)
            ->label(__('app.label.show_on_site'))
            ->sortable()
            ->onIcon('heroicon-m-check-circle')
            ->offIcon('heroicon-m-x-circle')
            ->onColor('success')
            ->offColor('danger');
    }
}
