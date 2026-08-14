<?php

namespace App\Filament\Pages\Blocks;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;

class SaveAction
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
                ->action(function (Action $action) use ($key, $tabClass): void {
                    /**
                     * The state is read from the tab this button sits in rather
                     * than from the whole form: a page holds several tabs, and
                     * validating all of them would let a required field the
                     * editor never opened block a save it has nothing to do
                     * with.
                     */
                    $tab = $action->getSchemaComponent()?->getContainer()->getParentComponent();

                    $state = $tab?->getChildSchema()?->getState() ?? [];

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
