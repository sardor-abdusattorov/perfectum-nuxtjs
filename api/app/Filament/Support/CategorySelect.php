<?php

namespace App\Filament\Support;

use App\Enums\CategoryType;
use App\Models\Category;
use Filament\Forms\Components\Select;

class CategorySelect
{
    public static function make(CategoryType $type, string $field = 'category_id'): Select
    {
        return Select::make($field)
            ->label(__('app.label.category'))
            ->helperText(__('app.helper.entity_category'))
            ->options(Category::options($type))
            ->searchable();
    }
}
