<?php

namespace App\Filament\Pages\Cdma;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\ContactCardIcon;
use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Filament\Pages\Blocks\ContentTab;
use App\Filament\Pages\Blocks\SaveAction;
use App\Filament\Support\Fields;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs\Tab;

class SupportTab extends ContentTab
{
    public static function key(): ContentBlockKey
    {
        return ContentBlockKey::Support;
    }

    public static function page(): PageKey
    {
        return PageKey::Cdma;
    }

    public static function make(): Tab
    {
        return Tab::make(__('app.section.support'))
            ->schema([
                Repeater::make('support.cards')
                    ->label(__('app.label.cards'))
                    ->addActionLabel(__('app.action.add'))
                    ->schema([
                        Select::make('icon')
                            ->label(__('app.label.card_icon'))
                            ->options(ContactCardIcon::getIconOptions())
                            ->allowHtml()
                            ->searchable()
                            ->native(false)
                            ->placeholder(__('app.placeholder.no_icon')),

                        TranslatableTabs::make('support_translations')
                            ->schema([
                                TextInput::make('title')
                                    ->label(__('app.label.title'))
                                    ->required(),

                                TextInput::make('value')
                                    ->label(__('app.label.value')),

                                TextInput::make('note')
                                    ->label(__('app.label.note')),
                            ]),

                        TextInput::make('url')
                            ->label(__('app.label.url'))
                            ->helperText(__('app.helper.support_url')),

                        Fields::status(),
                    ])
                    ->itemLabel(Fields::itemLabel('title'))
                    ->defaultItems(0)
                    ->reorderable()
                    ->collapsible(),

                SaveAction::make(self::class),
            ]);
    }
}
