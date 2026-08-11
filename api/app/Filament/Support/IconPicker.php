<?php

namespace App\Filament\Support;

use App\Enums\SocialIcon;
use App\Support\IconName;
use Filament\Forms\Components\Select;

class IconPicker
{
    public static function make(string $field = 'icon'): Select
    {
        return Select::make($field)
            ->label(__('app.label.icon'))
            ->helperText(__('app.helper.icon'))
            ->options(SocialIcon::getIconOptions())
            ->allowHtml()
            ->searchable()
            ->native(false)
            ->required()
            ->afterStateHydrated(fn (Select $component, ?string $state) => $component->state(IconName::blade($state)));
    }
}
