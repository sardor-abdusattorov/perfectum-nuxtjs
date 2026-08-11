<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CategoryType: string implements HasLabel
{
    case News = 'news';
    case Action = 'action';
    case Faq = 'faq';
    case Device = 'device';

    public function getLabel(): string
    {
        return __("app.category_type.{$this->value}");
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
