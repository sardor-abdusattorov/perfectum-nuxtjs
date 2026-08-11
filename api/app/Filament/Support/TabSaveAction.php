<?php

namespace App\Filament\Support;

use App\Filament\Pages\Homepage\ContentTab;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;

class TabSaveAction
{
    /**
     * @param  class-string<ContentTab>  $tabClass
     */
    public static function make(string $tabClass): Actions
    {
        $key = $tabClass::key()->value;

        return Actions::make([
            Action::make("save_{$key}")
                ->label(__('app.action.save'))
                ->keyBindings(['mod+s'])
                ->action(function ($livewire) use ($key, $tabClass): void {
                    $state = $livewire->form->getState();

                    $tabClass::save($state[$key] ?? []);

                    Notification::make()
                        ->success()
                        ->title(__('app.notification.saved'))
                        ->send();
                }),
        ])
            ->columnSpanFull();
    }
}
