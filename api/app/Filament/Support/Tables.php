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
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;

class Tables
{
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

    /**
     * The site is two sections sharing one panel, and a listing that holds
     * both — news, promos, services — is read one section at a time.
     */
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
     * One dropdown per row instead of a strip of labels: four actions side by
     * side pushed the row past its edge, and an unlabeled icon among labeled
     * buttons read as noise. In the dropdown every action keeps its full name.
     *
     * The preview action hides itself for a record that has no address on the
     * site, so every listing may offer it and only the ones with pages show it.
     *
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
