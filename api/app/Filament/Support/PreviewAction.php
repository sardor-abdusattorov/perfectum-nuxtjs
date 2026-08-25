<?php

declare(strict_types=1);

namespace App\Filament\Support;

use App\Support\PreviewToken;
use Closure;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;

/**
 * Opens the record on the site the way a visitor will see it, published or not.
 * The address carries a token good for this one record and for a day, so a
 * draft can be sent to whoever signs it off without publishing it first.
 *
 * Each resource says where its record lives, because only the resource knows:
 * `PreviewAction::make(fn (News $record): string => "/news/{$record->slug}")`.
 */
class PreviewAction
{
    public static function make(Closure $path): Action
    {
        return Action::make('preview')
            ->label(__('app.label.preview'))
            ->tooltip(__('app.helper.preview'))
            ->icon(Heroicon::Eye)
            ->color('gray')
            ->url(fn (Model $record): string => self::url($record, $path), shouldOpenInNewTab: true);
    }

    private static function url(Model $record, Closure $path): string
    {
        $site = rtrim((string) config('app.frontend_url'), '/');

        return $site
            .'/'.app()->getLocale()
            .'/'.ltrim($path($record), '/')
            .'?'.PreviewToken::PARAM.'='.urlencode(PreviewToken::for($record));
    }
}
