<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum OfficeType: string implements HasColor, HasLabel
{
    case Office = 'office';
    case Dealer = 'dealer';

    public function getLabel(): string
    {
        return __("app.office_type.{$this->value}");
    }

    public function getColor(): string
    {
        return $this === self::Office ? 'danger' : 'gray';
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
