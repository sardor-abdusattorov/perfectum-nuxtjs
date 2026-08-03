<?php

declare(strict_types=1);

namespace App\Filament\Resources\Activities;

use App\Filament\Resources\Activities\Pages\ListActivities;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use MrAdder\FilamentLogger\Resources\ActivityResource as BaseResource;
use MrAdder\FilamentLogger\Resources\ActivityResource\Support\ActivityResourceTableOptions;

/**
 * Localized wrapper around the filament-logger activity resource: the package
 * hard-codes English labels for the risk column and stores raw `log_name` /
 * `event` values, so both are routed through the published translation file.
 */
class ActivityResource extends BaseResource
{
    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    /**
     * @return array<int, TextColumn>
     */
    protected static function getTableColumns(): array
    {
        return [
            TextColumn::make('log_name')
                ->badge()
                ->colors(ActivityResourceTableOptions::logNameColors())
                ->label(static::resourceLabel('type'))
                ->formatStateUsing(fn (?string $state): string => static::translateState('log_name', $state))
                ->sortable(),

            TextColumn::make('event')
                ->label(static::resourceLabel('event'))
                ->formatStateUsing(fn (?string $state): string => static::translateState('event', $state))
                ->sortable(),

            TextColumn::make('properties.risk')
                ->label(static::resourceLabel('risk'))
                ->badge()
                ->toggleable()
                ->color(fn (?string $state): string => match ($state) {
                    'high' => 'danger',
                    'medium' => 'warning',
                    default => 'gray',
                })
                ->formatStateUsing(fn (?string $state): string => static::translateState('risk', $state)),

            TextColumn::make('description')
                ->label(static::resourceLabel('description'))
                ->toggleable()
                ->toggledHiddenByDefault()
                ->wrap(),

            TextColumn::make('subject_type')
                ->label(static::resourceLabel('subject'))
                ->formatStateUsing(fn ($state, Model $record): string => static::formatSubjectState($state, $record)),

            TextColumn::make('causer.name')
                ->label(static::resourceLabel('user')),

            TextColumn::make('created_at')
                ->label(static::resourceLabel('logged_at'))
                ->dateTime(static::defaultDateTimeFormat(), config('app.timezone'))
                ->sortable(),
        ];
    }

    protected static function getListActivitiesPage(): string
    {
        return ListActivities::class;
    }

    /**
     * Translate a value stored in the activity table. Falls back to a headline
     * of the raw value so unmapped events stay readable.
     */
    protected static function translateState(string $group, ?string $state): string
    {
        if (blank($state)) {
            return '-';
        }

        $key = "filament-logger::filament-logger.{$group}.".Str::lower(str_replace(' ', '_', $state));
        $translation = __($key);

        return is_string($translation) && $translation !== $key
            ? $translation
            : Str::headline($state);
    }
}
