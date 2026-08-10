<?php

namespace App\Filament\Support;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;

class TabSaveAction
{
    /**
     * Save button for a single tab of a content page.
     *
     * @param  string  $key  section key inside $data, e.g. 'hero'
     * @param  class-string  $tabClass  tab class exposing a static save(array $data)
     */
    public static function make(string $key, string $tabClass): Actions
    {
        return Actions::make([
            Action::make("save_{$key}")
                ->label(__('app.action.save'))
                ->keyBindings(['mod+s'])
                ->action(function ($livewire) use ($key, $tabClass): void {
                    // Dehydrated state, not the raw Livewire property: rich
                    // text fields hold an editor document until dehydration
                    // turns them into HTML strings.
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
