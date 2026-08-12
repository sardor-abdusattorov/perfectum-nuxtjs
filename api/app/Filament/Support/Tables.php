<?php

namespace App\Filament\Support;

use App\Enums\PublishedStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
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
            ViewAction::make(),
            EditAction::make(),
            DeleteAction::make(),
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
