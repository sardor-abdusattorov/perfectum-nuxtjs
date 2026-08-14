<?php

namespace App\Filament\Resources\Applications\Schemas;

use App\Filament\Resources\Applications\Actions\ChangeApplicationStatusAction;
use App\Models\Application;
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
                    ->columns(2)
                    ->schema([
                        TextEntry::make('status')
                            ->label(__('app.label.status'))
                            ->badge()
                            ->color(fn (?string $state): string => Application::statusColor($state))
                            ->formatStateUsing(fn (?string $state): string => Application::statusLabel($state))
                            ->hintAction(ChangeApplicationStatusAction::make()),

                        TextEntry::make('theme')
                            ->label(__('app.label.application_theme'))
                            ->badge()
                            ->formatStateUsing(fn (?string $state): string => Application::themeLabel($state)),

                        TextEntry::make('created_at')
                            ->label(__('app.label.created_at'))
                            ->dateTime('d.m.Y H:i')
                            ->placeholder('—'),

                        TextEntry::make('updated_at')
                            ->label(__('app.label.updated_at'))
                            ->dateTime('d.m.Y H:i')
                            ->placeholder('—'),

                        TextEntry::make('name')
                            ->label(__('app.label.name'))
                            ->placeholder('—'),

                        TextEntry::make('phone')
                            ->label(__('app.label.phone')),

                        TextEntry::make('email')
                            ->label(__('app.label.email'))
                            ->placeholder('—'),

                        TextEntry::make('ip_address')
                            ->label(__('app.label.ip_address'))
                            ->placeholder('—'),

                        TextEntry::make('message')
                            ->label(__('app.label.message'))
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}