<?php

namespace App\Filament\Support;

use App\Enums\SocialIcon;
use App\Support\IconName;
use Filament\Forms\Components\Select;

class IconPicker
{
    /**
     * A fixed list of the networks the site actually links to, rather than a
     * browser over every icon set installed. Records seeded with an Iconify
     * name are mapped onto the matching option when the form loads.
     */
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
