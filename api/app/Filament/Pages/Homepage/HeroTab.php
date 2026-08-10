<?php

namespace App\Filament\Pages\Homepage;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\ContentBlockKey;
use App\Filament\Support\ImageUpload;
use App\Filament\Support\TabSaveAction;
use App\Filament\Support\TextEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;

class HeroTab extends ContentTab
{
    public static function key(): ContentBlockKey
    {
        return ContentBlockKey::Hero;
    }

    public static function make(): Tab
    {
        return Tab::make(__('app.section.hero'))
            ->schema([
                TranslatableTabs::make('translations')
                    ->schema([
                        TextEditor::make('hero.title')
                            ->label(__('app.label.title'))
                            ->extraInputAttributes([
                                'style' => 'min-height: 8rem; max-height: 30vh; overflow-y: auto;',
                            ]),

                        TextInput::make('hero.description')
                            ->label(__('app.label.description')),

                        TextEditor::make('hero.mobile_text')
                            ->label(__('app.label.hero_mobile_text')),
                    ]),

                ImageUpload::make('content-blocks', 'hero.image')
                    ->label(__('app.label.image')),

                Section::make(__('app.label.button'))
                    ->schema([
                        TranslatableTabs::make('button_translations')
                            ->schema([
                                TextInput::make('hero.button.label')
                                    ->label(__('app.label.button_label')),
                            ]),

                        TextInput::make('hero.button.url')
                            ->label(__('app.label.url')),

                        Toggle::make('hero.button.status')
                            ->label(__('app.label.show_on_site'))
                            ->default(true),
                    ]),

                Section::make(__('app.label.app_links'))
                    ->schema([
                        TextInput::make('hero.app_store_url')
                            ->label(__('app.label.app_store_url')),

                        TextInput::make('hero.google_play_url')
                            ->label(__('app.label.google_play_url')),
                    ]),

                TabSaveAction::make('hero', self::class),
            ]);
    }
}
