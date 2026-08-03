<?php

declare(strict_types=1);

namespace App\Enums;

enum PublishedStatus: int
{
    case Published = 1;
    case Unpublished = 0;

    public function getLabel(): string
    {
        return match ($this) {
            self::Published => __('app.status.published'),
            self::Unpublished => __('app.status.unpublished'),
        };
    }

    /**
     * @return array<int, string>
     */
    public static function getStatusOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $status): array => [$status->value => $status->getLabel()])
            ->all();
    }
}
