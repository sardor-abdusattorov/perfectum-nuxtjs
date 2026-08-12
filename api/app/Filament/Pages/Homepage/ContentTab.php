<?php

namespace App\Filament\Pages\Homepage;

use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Models\ContentBlock;
use Filament\Schemas\Components\Tabs\Tab;

abstract class ContentTab
{
    abstract public static function key(): ContentBlockKey;

    abstract public static function make(): Tab;

    public static function page(): PageKey
    {
        return PageKey::Home;
    }

    /**
     * @return array<string, mixed>
     */
    public static function load(): array
    {
        return ContentBlock::read(static::page(), static::key());
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function save(array $data): void
    {
        ContentBlock::write(static::page(), static::key(), $data);
    }
}
