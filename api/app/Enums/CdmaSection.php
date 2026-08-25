<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * The places the CDMA landing's own navigation can point at. Most are blocks
 * further down the same page, one is a page of its own — the site tells them
 * apart by the leading '#', so the panel never has to.
 *
 * The order and the wording of the links are the admin's; where each one leads
 * is the layout's, and stays here.
 */
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
