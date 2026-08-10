<?php

declare(strict_types=1);

namespace App\Enums;

enum ContentBlockKey: string
{
    case Hero = 'hero';
    case Marquee = 'marquee';
    case Choose = 'choose';
    case Tariffs = 'tariffs';
    case Features = 'features';
    case Coverage = 'coverage';
    case AppPromo = 'app_promo';
}
