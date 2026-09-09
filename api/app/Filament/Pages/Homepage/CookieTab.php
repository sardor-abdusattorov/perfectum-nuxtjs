<?php

namespace App\Filament\Pages\Homepage;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Filament\Pages\Blocks\ContentTab;
use App\Filament\Pages\Blocks\SaveAction;
use App\Filament\Support\Fields;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;

/**
 * The bar itself stands on every page, not only this one — it is edited here
 * because this is where the site's own wording lives, and travels to the
 * frontend inside the shared /site payload rather than with the homepage
 * blocks. The text is an editor and not a plain field so the notice can link
 * to the cookie policy, which a translation row could never hold.
 */
class CookieTab extends ContentTab
{
    public static function key(): ContentBlockKey
    {
        return ContentBlockKey::Cookie;
    }

    public static function page(): PageKey
    {
        return PageKey::Home;
    }

    public static function make(): Tab
    {
        return Tab::make(__('app.section.cookie'))
            ->schema([
                Section::make(__('app.label.section_texts'))
                    ->schema([
                        TranslatableTabs::make('translations')
                            ->schema([
                                Fields::multiline('cookie.text')
                                    ->label(__('app.label.cookie_text'))
                                    ->helperText(__('app.helper.cookie_text')),

                                TextInput::make('cookie.accept')
                                    ->label(__('app.label.cookie_accept'))
                                    ->helperText(__('app.helper.cookie_accept'))
                                    ->maxLength(60),
                            ]),
                    ]),

                SaveAction::make(self::class),
            ]);
    }
}
