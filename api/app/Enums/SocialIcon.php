<?php

declare(strict_types=1);

namespace App\Enums;

use App\Support\IconName;
use Filament\Support\Contracts\HasLabel;

enum SocialIcon: string implements HasLabel
{
    case Instagram = 'si-instagram';
    case Facebook = 'si-facebook';
    case Telegram = 'si-telegram';
    case YouTube = 'si-youtube';
    case TikTok = 'si-tiktok';
    case X = 'si-x';
    case LinkedIn = 'brand-linkedin';
    case WhatsApp = 'si-whatsapp';
    case Threads = 'si-threads';
    case Viber = 'si-viber';
    case Odnoklassniki = 'si-odnoklassniki';
    case VK = 'si-vk';

    public function getLabel(): string
    {
        return match ($this) {
            self::X => 'X (Twitter)',
            self::VK => 'VKontakte',
            default => $this->name,
        };
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

    /**
     * @return array<string, string>
     */
    public static function getIconOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case): array => [$case->value => $case->toHtml()])
            ->all();
    }

    public function toHtml(): string
    {
        $svg = IconName::svg($this->value, ['style' => 'width:1.15rem;height:1.15rem;flex:none']) ?? '';

        return '<span style="display:inline-flex;align-items:center;gap:.5rem">'
            .$svg
            .'<span>'.e($this->getLabel()).'</span>'
            .'</span>';
    }
}
