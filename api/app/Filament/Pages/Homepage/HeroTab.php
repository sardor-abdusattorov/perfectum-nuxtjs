<?php

namespace App\Filament\Pages\Homepage;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\ContentBlockKey;
use App\Filament\Support\ImageUpload;
use App\Filament\Support\StatusToggle;
use App\Filament\Support\TabSaveAction;
use App\Filament\Support\TextEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
                Section::make(__('app.label.slides'))
                    ->description(__('app.helper.hero_slides'))
                    ->schema([
                        Repeater::make('hero.slides')
                            ->hiddenLabel()
                            ->addActionLabel(__('app.action.add'))
                            ->schema([
                                TranslatableTabs::make('slide_translations')
                                    ->schema([
                                        TextInput::make('description')
                                            ->label(__('app.label.eyebrow')),

                                        TextEditor::make('title')
                                            ->label(__('app.label.title'))
                                            ->helperText(__('app.helper.hero_title'))
                                            ->extraInputAttributes([
                                                'style' => 'min-height: 8rem; max-height: 30vh; overflow-y: auto;',
                                            ]),

                                        TextEditor::make('lead')
                                            ->label(__('app.label.lead_text'))
                                            ->helperText(__('app.helper.hero_lead')),
                                    ]),

                                ImageUpload::make('content-blocks', 'image')
                                    ->label(__('app.label.image')),

                                StatusToggle::make(),
                            ])
                            ->itemLabel(fn (array $state): ?string => is_array($state['title'] ?? null)
                                ? strip_tags((string) reset($state['title']))
                                : null)
                            ->defaultItems(1)
                            ->reorderable()
                            ->collapsible(),
                    ]),

                Section::make(__('app.label.buttons'))
                    ->description(__('app.helper.hero_shared_parts'))
                    ->schema([
                        Repeater::make('hero.buttons')
                            ->hiddenLabel()
                            ->addActionLabel(__('app.action.add'))
                            ->schema([
                                TranslatableTabs::make('button_translations')
                                    ->schema([
                                        TextInput::make('label')
                                            ->label(__('app.label.button_label')),
                                    ]),

                                TextInput::make('url')
                                    ->label(__('app.label.url')),

                                Select::make('style')
                                    ->label(__('app.label.button_style'))
                                    ->options([
                                        'primary' => __('app.button_style.primary'),
                                        'secondary' => __('app.button_style.secondary'),
                                    ])
                                    ->default('primary'),

                                StatusToggle::make(),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),
                    ]),

                Section::make(__('app.label.app_links'))
                    ->schema([
                        TextInput::make('hero.app_store_url')
                            ->label(__('app.label.app_store_url')),

                        TextInput::make('hero.google_play_url')
                            ->label(__('app.label.google_play_url')),
                    ])
                    ->columns(2),

                TabSaveAction::make('hero', self::class),
            ]);
    }
}
