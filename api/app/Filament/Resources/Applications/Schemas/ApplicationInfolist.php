<?php

namespace App\Filament\Resources\Applications\Schemas;

use App\Filament\Resources\Applications\Actions\ChangeApplicationStatusAction;
use App\Models\Application;
use App\Models\ApplicationNote;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ApplicationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('app.label.application_single'))
                    ->columns(1)
                    ->schema([
                        TextEntry::make('status.name')
                            ->label(__('app.label.status'))
                            ->badge()
                            ->color(fn (Application $record): string => $record->status?->color ?? 'gray')
                            ->placeholder('—')
                            ->hintAction(ChangeApplicationStatusAction::make()),

                        TextEntry::make('processed_at')
                            ->label(__('app.label.answered_at'))
                            ->dateTime('d.m.Y H:i')
                            ->placeholder('—'),

                        TextEntry::make('handling_time')
                            ->label(__('app.label.handling_time'))
                            ->state(fn (Application $record): ?string => Application::readableHandlingTime($record->handlingSeconds()))
                            ->placeholder('—'),

                        TextEntry::make('theme.name')
                            ->label(__('app.label.application_theme'))
                            ->badge()
                            ->placeholder('—'),

                        TextEntry::make('name')
                            ->label(__('app.label.name'))
                            ->placeholder('—'),

                        TextEntry::make('message')
                            ->label(__('app.label.message'))
                            ->placeholder('—')
                            ->columnSpanFull(),

                        TextEntry::make('phone')
                            ->label(__('app.label.phone')),

                        TextEntry::make('email')
                            ->label(__('app.label.email'))
                            ->placeholder('—'),

                        TextEntry::make('ip_address')
                            ->label(__('app.label.ip_address'))
                            ->placeholder('—'),

                        TextEntry::make('created_at')
                            ->label(__('app.label.created_at'))
                            ->dateTime('d.m.Y H:i')
                            ->placeholder('—'),

                        TextEntry::make('updated_at')
                            ->label(__('app.label.updated_at'))
                            ->dateTime('d.m.Y H:i')
                            ->placeholder('—'),
                    ]),

                Section::make(__('app.label.note_journal'))
                    ->description(__('app.helper.note_journal'))
                    ->columns(1)
                    ->schema([
                        RepeatableEntry::make('notes')
                            ->hiddenLabel()
                            ->placeholder('—')
                            ->columns(1)
                            ->schema([
                                TextEntry::make('body')
                                    ->hiddenLabel()
                                    ->columnSpanFull(),

                                TextEntry::make('created_at')
                                    ->hiddenLabel()
                                    ->dateTime('d.m.Y H:i')
                                    ->badge()
                                    ->color('gray')
                                    ->formatStateUsing(fn (string $state, ApplicationNote $record): string => $record->authorLabel().' · '.$state),
                            ]),
                    ]),
            ]);
    }
}
