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
    case Cookie = 'cookie';
    case PageHero = 'page_hero';
    case Sections = 'sections';
    case Stats = 'stats';
    case Intro = 'intro';
    case Timeline = 'timeline';
    case Cards = 'cards';
    case Steps = 'steps';
    case Support = 'support';
    case Cta = 'cta';
}
