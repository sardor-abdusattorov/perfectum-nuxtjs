<?php

namespace App\Filament\Resources\Applications\Actions;

use App\Models\Application;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;

class ChangeApplicationStatusAction
{
    public static function make(string $name = 'changeStatus'): Action
    {
        return Action::make($name)
            ->label(__('app.label.change_status'))
            ->icon('heroicon-m-arrow-path')
            ->modalWidth('md')
            ->visible(fn(Application $record): bool => Gate::allows('update', $record))
            ->schema(self::schema())
            ->fillForm(fn(Application $record): array => [
                'status' => $record->status,
            ])
            ->action(fn(Application $record, array $data) => $record->update($data))
            ->successNotificationTitle(__('app.message.status_updated'));
    }

    public static function bulk(string $name = 'changeStatus'): BulkAction
    {
        return BulkAction::make($name)
            ->label(__('app.label.change_status_bulk'))
            ->icon('heroicon-m-arrow-path')
            ->modalWidth('md')
            ->authorizeIndividualRecords('update')
            ->schema(self::schema())
            ->action(fn(Builder $query, array $data) => $query->update([
                'status' => $data['status'],
            ]))
            ->deselectRecordsAfterCompletion()
            ->successNotificationTitle(__('app.message.status_updated'));
    }

    /**
     * @return array<int, Select>
     */
    private static function schema(): array
    {
        return [
            Select::make('status')
                ->label(__('app.label.status'))
                ->options(Application::getStatusOptions())
                ->native(false)
                ->required(),
        ];
    }
}