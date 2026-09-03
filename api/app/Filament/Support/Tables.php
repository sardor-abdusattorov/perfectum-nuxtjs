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
            ->sortable()
            ->alignEnd()
            ->toggleable(isToggledHiddenByDefault: true);
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
