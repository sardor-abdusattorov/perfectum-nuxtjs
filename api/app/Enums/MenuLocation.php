<?php

declare(strict_types=1);

namespace App\Enums;

enum MenuLocation: string
{
    case Header = 'header';
    case Footer = 'footer';

    public function getLabel(): string
    {
        return match ($this) {
            self::Header => __('app.label.menu_header'),
            self::Footer => __('app.label.menu_footer'),
        };
    }

    /**
     * @return array<string, string>
     */
    public static function getLocationOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $location): array => [$location->value => $location->getLabel()])
            ->all();
    }
}
