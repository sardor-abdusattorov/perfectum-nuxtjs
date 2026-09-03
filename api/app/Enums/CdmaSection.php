<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CdmaSection: string implements HasLabel
{
    case Tariffs = 'tariffs';
    case Services = 'services';
    case Numbers = 'numbers';
    case Faq = 'faq';
    case Support = 'support';
    case News = 'news';
    case Promo = 'promo';
    case Dealers = 'dealers';

    public function target(): string
    {
        return match ($this) {
            self::Dealers => '/cdma/dealers',
            default => '#cdma-'.$this->value,
        };
    }

    public function getLabel(): string
    {
        return __('app.cdma_section.'.$this->value);
    }

    /**
     * @return array<string, string>
     */
    public static function getSectionOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case): array => [$case->value => $case->getLabel()])
            ->all();
    }
}
