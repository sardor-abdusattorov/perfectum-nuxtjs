<?php

declare(strict_types=1);

namespace App\Enums;

use App\Models\Social;
use Filament\Support\Contracts\HasLabel;

enum ContactCardIcon: string implements HasLabel
{
    case Building = 'heroicon-o-building-office';
    case Phone = 'heroicon-o-phone';
    case Envelope = 'heroicon-o-envelope';
    case Globe = 'heroicon-o-globe-alt';
    case MapPin = 'heroicon-o-map-pin';
    case Clock = 'heroicon-o-clock';
    case Chat = 'heroicon-o-chat-bubble-left-right';
    case Users = 'heroicon-o-user-group';
    case Lifebuoy = 'heroicon-o-lifebuoy';
    case Briefcase = 'heroicon-o-briefcase';
    case Mobile = 'heroicon-o-device-phone-mobile';
    case AtSymbol = 'heroicon-o-at-symbol';

    public function getLabel(): string
    {
        return __('app.contact_card_icon.'.strtolower($this->name));
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
        $svg = Social::iconSvg($this->value, ['style' => 'width:1.15rem;height:1.15rem;flex:none']) ?? '';

        return '<span style="display:inline-flex;align-items:center;gap:.5rem">'
            .$svg
            .'<span>'.e($this->getLabel()).'</span>'
            .'</span>';
    }
}
