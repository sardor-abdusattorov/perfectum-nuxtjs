<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Network: string implements HasLabel
{
    case FiveG = '5g';
    case Cdma = 'cdma';
    case Both = 'both';

    public function getLabel(): string
    {
        return __("app.network.{$this->value}");
    }

    /**
     * @return array<string, string>
     */
    public static function getOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case): array => [$case->value => $case->getLabel()])
            ->all();
    }
}
