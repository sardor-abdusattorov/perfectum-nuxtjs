<?php

namespace App\Filament\Resources\Applications\Actions;

use App\Models\Application;
use App\Models\ApplicationStatus;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
            ->fillForm(fn (Application $record): array => ['status_id' => $record->status_id])
            ->action(function (Application $record, array $data): void {
                $record->update(['status_id' => $data['status_id']]);
                $record->addNote((string) ($data['note'] ?? ''));
            })
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
            ->schema(self::schema(withNote: false))
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
     * The note is written at the moment the status changes — «не отвечает,
     * перезвонить завтра» belongs next to the status it explains, and it is
     * added to the journal rather than replacing what is already there. A bulk
     * change has no note: one text over a hundred applications would say
     * nothing about any of them.
     *
     * @return array<int, Select|Textarea>
     */
    private static function schema(bool $withNote = true): array
    {
        return array_values(array_filter([
            Select::make('status_id')
                ->label(__('app.label.status'))
                ->options(ApplicationStatus::options())
                ->native(false)
                ->required(),

            $withNote ? Textarea::make('note')
                ->label(__('app.label.note_add'))
                ->helperText(__('app.helper.note_add'))
                ->rows(4)
                ->maxLength(2000) : null,
        ]));
    }
}
