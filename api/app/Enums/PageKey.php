<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PageKey: string implements HasLabel
{
    case Home = 'home';
    case Tariffs = 'tariffs';
    case Services = 'services';
    case Numbers = 'numbers';
    case Devices = 'devices';
    case Actions = 'actions';
    case News = 'news';
    case CoverageArea = 'coverage_area';
    case Offices = 'offices';
    case Documents = 'documents';
    case Contacts = 'contacts';
    case Help = 'help';
    case Faq = 'faq';
    case AboutCompany = 'about_company';
    case Careers = 'careers';
    case Procurement = 'procurement';
    case Cdma = 'cdma';
    case CdmaConnect = 'cdma_connect';

    public function getLabel(): string
    {
        return __("app.page.{$this->value}");
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
