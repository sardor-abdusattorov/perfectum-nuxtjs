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

class PreviewAction
{
    public const DRAFT = 'draft=1';

    public static function make(): Action
    {
        return Action::make('preview')
            ->label(__('app.label.preview'))
            ->tooltip(fn (Model $record): string => __(self::permanent($record) ? 'app.helper.preview_by_link' : 'app.helper.preview'))
            ->icon(Heroicon::ArrowTopRightOnSquare)
            ->color('gray')
            ->visible(fn (Model $record): bool => self::path($record) !== null)
            ->url(fn (Model $record): string => self::url($record), shouldOpenInNewTab: true);
    }

    /**
     * A record opened by its own address needs no token, and handing one out
     * anyway would be the wrong thing to copy into a chat: it expires in a day
     * and the plain address does not.
     */
    private static function permanent(Model $record): bool
    {
        return $record->isByLinkOnly();
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

        $address = $site.'/'.app()->getLocale().self::path($record);

        /**
         * Метке верит серверный плагин счётчиков: несогласованный текст не
         * должен уезжать в Яндекс.Метрику, а при включённом Вебвизоре — и
         * содержимым страницы. Адрес работает и без неё, просто со счётчиком.
         */
        if (self::permanent($record)) {
            return $address.'?'.self::DRAFT;
        }

        return $address.'?'.PreviewToken::PARAM.'='.urlencode(PreviewToken::for($record));
    }
}
