<?php

declare(strict_types=1);

namespace App\Enums;

enum MenuLocation: string
{
    case Header = 'header';
    case Footer = 'footer';
    case CdmaHeader = 'cdma_header';
    case CdmaFooter = 'cdma_footer';

    public function getLabel(): string
    {
        return __("app.menu_location.{$this->value}");
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
