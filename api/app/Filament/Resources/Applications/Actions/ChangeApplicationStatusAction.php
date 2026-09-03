<?php

namespace App\Filament\Resources\Applications\Actions;

use App\Models\Application;
use App\Models\ApplicationStatus;
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
            ->visible(fn (Application $record): bool => Gate::allows('update', $record))
            ->schema(self::schema())
            ->fillForm(fn (Application $record): array => [
                'status_id' => $record->status_id,
            ])
            ->action(fn (Application $record, array $data) => $record->update($data))
            ->successNotificationTitle(__('app.message.status_updated'));
    }

    /**
     * A query builder update never fires a model event, so the handling time
     * the model stamps on save is written here alongside the status itself.
     */
    public static function bulk(string $name = 'changeStatus'): BulkAction
    {
        return BulkAction::make($name)
            ->label(__('app.label.change_status_bulk'))
            ->icon('heroicon-m-arrow-path')
            ->modalWidth('md')
            ->authorizeIndividualRecords('update')
            ->schema(self::schema())
            ->action(function (Builder $query, array $data): void {
                $status = ApplicationStatus::query()->find($data['status_id']);

                $query->update(['status_id' => $data['status_id']]);

                if ($status !== null && ! $status->is_default) {
                    (clone $query)->whereNull('processed_at')->update(['processed_at' => now()]);
                }
            })
            ->deselectRecordsAfterCompletion()
            ->successNotificationTitle(__('app.message.status_updated'));
    }

    /**
     * @return array<int, Select>
     */
    private static function schema(): array
    {
        return [
            Select::make('status_id')
                ->label(__('app.label.status'))
                ->options(ApplicationStatus::options())
                ->native(false)
                ->required(),
        ];
    }
}
