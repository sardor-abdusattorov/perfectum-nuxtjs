<?php

namespace App\Filament\Support;

use App\Enums\CategoryType;
use App\Models\Category;
use Filament\Tables\Filters\SelectFilter;

class CategoryFilter
{
    public static function make(CategoryType $type, string $field = 'category_id'): SelectFilter
    {
        return SelectFilter::make($field)
            ->label(__('app.label.category'))
            ->options(Category::options($type));
    }
}
