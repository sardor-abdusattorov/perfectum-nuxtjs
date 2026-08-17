<?php

namespace App\Filament\Pages\Contacts;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\ContactCard;
use App\Enums\ContactCardIcon;
use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Filament\Pages\Blocks\ContentTab;
use App\Filament\Pages\Blocks\SaveAction;
use App\Filament\Support\Fields;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;

class CardsTab extends ContentTab
{
    public static function key(): ContentBlockKey
    {
        return ContentBlockKey::Cards;
    }

    public static function page(): PageKey
    {
        return PageKey::Contacts;
    }

    public static function make(): Tab
    {
        return Tab::make(__('app.section.cards'))
            ->schema([
                Repeater::make('cards.items')
                    ->label(__('app.label.cards'))
                    ->itemLabel(Fields::itemLabel('title'))
                    ->collapsible()
                    ->reorderable()
                    ->defaultItems(0)
                    ->schema([
                        Select::make('type')
                            ->label(__('app.label.card_type'))
                            ->helperText(__('app.helper.contact_card_type'))
                            ->options(ContactCard::getOptions())
                            ->required(),

                        Select::make('icon')
                            ->label(__('app.label.card_icon'))
                            ->helperText(__('app.helper.contact_card_icon'))
                            ->options(ContactCardIcon::getIconOptions())
                            ->allowHtml()
                            ->searchable()
                            ->native(false)
                            ->placeholder(__('app.placeholder.no_icon')),

                        TranslatableTabs::make('translations')
                            ->schema([
                                TextInput::make('title')
                                    ->label(__('app.label.title'))
                                    ->required(),

                                Textarea::make('text')
                                    ->label(__('app.label.text'))
                                    ->helperText(__('app.helper.contact_card_text'))
                                    ->rows(2),

                                Textarea::make('note')
                                    ->label(__('app.label.note'))
                                    ->rows(2),
                            ]),

                        Fields::status(),
                    ]),

                Section::make(__('app.label.section_texts'))
                    ->schema([
                        TranslatableTabs::make('translations')
                            ->schema([
                                Fields::multiline('cards.note')
                                    ->label(__('app.label.note')),
                            ]),
                    ]),

                SaveAction::make(self::class),
            ]);
    }
}
