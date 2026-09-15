<?php

namespace App\Filament\Support;

use App\Enums\Network;
use App\Enums\PublishedStatus;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;

class Tables
{
    /**
     * How many readers the record has had. Sortable, so the list answers
     * «what is being read» without leaving the panel; hidden by default
     * because it is a number to look up, not one to scan past every day.
     */
    public static function viewsColumn(string $field = 'views'): TextColumn
    {
        return TextColumn::make($field)
            ->label(__('app.label.views'))
            ->numeric()
            ->default(0)
            ->sortable()
            ->alignEnd()
            ->toggleable();
    }

    public static function statusColumn(string $field = 'status'): ToggleColumn
    {
        return ToggleColumn::make($field)
            ->label(__('app.label.show_on_site'))
            ->sortable()
            ->onIcon('heroicon-m-check-circle')
            ->offIcon('heroicon-m-x-circle')
            ->onColor('success')
            ->offColor('danger');
    }

    /**
     * Off for almost every record, so it stays out of the way until someone
     * asks for it — but it is a switch, not a badge: the whole point is to
     * close the link again without opening the record.
     */
    public static function byLinkColumn(string $field = 'by_link'): ToggleColumn
    {
        return ToggleColumn::make($field)
            ->label(__('app.label.by_link'))
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true)
            ->onIcon('heroicon-m-link')
            ->offIcon('heroicon-m-x-circle')
            ->onColor('warning')
            ->offColor('gray');
    }

    /**
     * Открытые ссылки копятся молча: запись уходит из списков на сайте и
     * вспомнить про неё неоткуда. Фильтр — единственный способ спросить
     * «что у нас сейчас открыто по ссылке».
     */
    public static function byLinkFilter(string $field = 'by_link'): SelectFilter
    {
        return SelectFilter::make($field)
            ->label(__('app.label.by_link'))
            ->options([
                '1' => __('app.label.by_link_open'),
                '0' => __('app.label.by_link_closed'),
            ]);
    }

    public static function statusFilter(string $field = 'status'): SelectFilter
    {
        return SelectFilter::make($field)
            ->label(__('app.label.status'))
            ->options(PublishedStatus::getStatusOptions());
    }

    public static function networkFilter(string $field = 'network'): SelectFilter
    {
        return SelectFilter::make($field)
            ->label(__('app.label.network'))
            ->options(Network::getOptions());
    }

    /**
     * @param  class-string  $taxonomy
     */
    public static function categoryFilter(string $taxonomy, string $field = 'category_id'): SelectFilter
    {
        return SelectFilter::make($field)
            ->label(__('app.label.category'))
            ->options($taxonomy::options());
    }

    /**
     * @return array<int, mixed>
     */
    public static function actions(): array
    {
        return [
            ActionGroup::make([
                PreviewAction::make(),
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
                ->label(__('app.label.actions'))
                ->button()
                ->color('gray')
                ->icon(Heroicon::ChevronDown)
                ->iconPosition(IconPosition::After),
        ];
    }

    /**
     * @return array<int, mixed>
     */
    public static function bulkActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ];
    }
}
