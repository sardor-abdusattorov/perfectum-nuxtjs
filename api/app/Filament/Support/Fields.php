<?php

namespace App\Filament\Support;

use App\Enums\CategoryType;
use App\Enums\SocialIcon;
use App\Models\Category;
use App\Support\IconName;
use App\Support\Slug;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class Fields
{
    public static function slug(string $field = 'slug'): TextInput
    {
        return TextInput::make($field)
            ->label(__('app.label.slug'))
            ->helperText(__('app.helper.slug'))
            ->unique(ignoreRecord: true)
            ->alphaDash()
            ->maxLength(255);
    }

    public static function slugPreview(string $field = 'slug'): Closure
    {
        return function (Set $set, Get $get, ?string $state, string $operation) use ($field): void {
            if ($operation === 'create' && blank($get($field))) {
                $set($field, Slug::base($state ?? ''));
            }
        };
    }

    public static function sort(string $field = 'sort'): TextInput
    {
        return TextInput::make($field)
            ->label(__('app.label.sort'))
            ->helperText(__('app.helper.sort'))
            ->numeric()
            ->default(0)
            ->required();
    }

    public static function status(string $field = 'status'): Toggle
    {
        return Toggle::make($field)
            ->label(__('app.label.show_on_site'))
            ->helperText(__('app.helper.if_disabled_not_shown'))
            ->default(true);
    }

    public static function multiline(string $field): Textarea
    {
        return Textarea::make($field)
            ->helperText(__('app.helper.line_breaks'))
            ->rows(2)
            ->autosize();
    }

    public static function editor(string $field): RichEditor
    {
        return RichEditor::make($field)
            ->fileAttachmentsDisk('public')
            ->fileAttachmentsDirectory(fn (): string => 'uploads/attachments/'.now()->format('Y/m'))
            ->fileAttachmentsVisibility('public')
            ->toolbarButtons([
                ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
                ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'paragraph'],
                ['alignStart', 'alignCenter', 'alignEnd', 'alignJustify'],
                ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                ['highlight', 'textColor', 'clearFormatting'],
                ['details', 'horizontalRule', 'lead', 'small', 'code'],
                ['table', 'attachFiles'],
                ['grid'],
                ['undo', 'redo'],
            ]);
    }

    public static function image(string $model, string $field = 'image'): FileUpload
    {
        return FileUpload::make($field)
            ->label(__('app.label.image'))
            ->disk('public')
            ->directory(fn (): string => "uploads/{$model}/".now()->format('Y/m'))
            ->visibility('public')
            ->image()
            ->imageEditor()
            ->previewable()
            ->downloadable()
            ->maxSize(6144)
            ->nullable();
    }

    public static function icon(string $field = 'icon'): Select
    {
        return Select::make($field)
            ->label(__('app.label.icon'))
            ->helperText(__('app.helper.icon'))
            ->options(SocialIcon::getIconOptions())
            ->allowHtml()
            ->searchable()
            ->native(false)
            ->required()
            ->afterStateHydrated(fn (Select $component, ?string $state) => $component->state(IconName::blade($state)));
    }

    public static function category(CategoryType $type, string $field = 'category_id'): Select
    {
        return Select::make($field)
            ->label(__('app.label.category'))
            ->helperText(__('app.helper.entity_category'))
            ->options(Category::options($type))
            ->searchable();
    }
}
