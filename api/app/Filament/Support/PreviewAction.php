<?php

declare(strict_types=1);

namespace App\Filament\Support;

use App\Enums\Network;
use App\Models\Action as ActionModel;
use App\Models\Device;
use App\Models\News;
use App\Models\Page;
use App\Models\Service;
use App\Models\Tariff;
use App\Models\Tender;
use App\Models\Vacancy;
use App\Support\PreviewToken;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;

/**
 * Opens the record on the site the way a visitor will see it, published or not.
 * The address carries a token good for this one record and for a day, so a
 * draft can be sent to whoever signs it off without publishing it first.
 *
 * The map below is the one place the admin knows where each record lives on
 * the site — a CDMA record lives in its own corner of the CDMA landing, and
 * the tariff's network comes from its category.
 */
class PreviewAction
{
    public static function make(): Action
    {
        return Action::make('preview')
            ->label(__('app.label.preview'))
            ->tooltip(__('app.helper.preview'))
            ->icon(Heroicon::ArrowTopRightOnSquare)
            ->color('gray')
            ->visible(fn (Model $record): bool => self::path($record) !== null)
            ->url(fn (Model $record): string => self::url($record), shouldOpenInNewTab: true);
    }

    private static function path(Model $record): ?string
    {
        $cdma = fn (Model $model): bool => $model->network === Network::Cdma;

        return match ($record::class) {
            Page::class => "/pages/{$record->slug}",
            News::class => ($cdma($record) ? '/cdma' : '')."/news/{$record->slug}",
            ActionModel::class => ($cdma($record) ? '/cdma' : '')."/actions/{$record->slug}",
            Service::class => ($cdma($record) ? '/cdma' : '')."/services/{$record->slug}",
            Tariff::class => ($record->category?->network === Network::Cdma ? '/cdma' : '')."/tariffs/{$record->slug}",
            Device::class => "/devices/{$record->slug}",
            Vacancy::class => "/careers/{$record->slug}",
            Tender::class => "/procurement/{$record->slug}",
            default => null,
        };
    }

    private static function url(Model $record): string
    {
        $site = rtrim((string) config('app.frontend_url'), '/');

        return $site
            .'/'.app()->getLocale()
            .self::path($record)
            .'?'.PreviewToken::PARAM.'='.urlencode(PreviewToken::for($record));
    }
}
