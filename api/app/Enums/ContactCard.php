<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * A card names which contacts it shows; the values themselves stay in the
 * site settings so an email is never edited in two places.
 */
enum ContactCard: string implements HasLabel
{
    case Office = 'office';
    case Phones = 'phones';
    case Emails = 'emails';
    case Socials = 'socials';

    public function getLabel(): string
    {
        return __("app.contact_card.{$this->value}");
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
