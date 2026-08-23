<?php

namespace App\Filament\Support;

use App\Enums\Network;
use App\Enums\SocialIcon;
use App\Models\Social;
use App\Support\Slug;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\RichEditorTool;
use Filament\Forms\Components\RichEditor\TextColor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Icons\Heroicon;

class Fields
{
    /**
     * The public disk is served straight out of the web root, where nginx hands
     * anything ending in .php to the interpreter, so every upload field that
     * writes there must name the types it accepts.
     *
     * @var array<int, string>
     */
    public const DOCUMENT_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    public static function itemLabel(string $field): Closure
    {
        return function (array $state) use ($field): ?string {
            $value = $state[$field] ?? null;

            if (is_array($value)) {
                $value = collect($value)->first(fn ($item): bool => filled($item));
            }

            $label = trim(strip_tags((string) $value));

            return $label === '' ? null : $label;
        };
    }

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
        return StatusToggle::make($field)
            ->label(__('app.label.show_on_site'))
            ->helperText(__('app.helper.if_disabled_not_shown'))
            ->default(true);
    }

    public static function multiline(string $field): RichEditor
    {
        return self::richEditor($field, 'editor-box_short')
            ->toolbarButtons([
                ['bold', 'italic', 'underline', 'strike', 'link'],
                ['textColor', 'clearFormatting'],
                ['undo', 'redo'],
                ['fullscreen'],
            ])
            ->textColors([
                'accent' => TextColor::make(__('app.color.accent'), '#e60000', '#ff4d4d'),
                'outline' => TextColor::make(__('app.color.outline'), '#0b0d17', '#ffffff'),
            ]);
    }

    public static function editor(string $field): RichEditor
    {
        return self::richEditor($field, 'editor-box_tall')
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
                ['fullscreen'],
            ]);
    }

    /**
     * A long article does not fit the box the form gives it, so the last
     * toolbar button lifts the editor over the page until it is pressed again
     * or Escape is hit. The expanded editor stays below the modal layer, so
     * attaching a file or editing a link still works from there.
     */
    private static function richEditor(string $field, string $size): RichEditor
    {
        return RichEditor::make($field)
            ->tools([
                RichEditorTool::make('fullscreen')
                    ->label(__('app.label.editor_fullscreen'))
                    ->icon(Heroicon::ArrowsPointingOut)
                    ->activeStyling(false)
                    ->jsHandler("\$el.closest('.fi-fo-rich-editor')?.classList.toggle('fi-fo-rich-editor-fullscreen')"),
            ])
            ->extraAttributes([
                'x-on:keydown.escape' => "\$el.classList.remove('fi-fo-rich-editor-fullscreen')",
            ])
            ->extraInputAttributes(['class' => "editor-box {$size}"]);
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

    public static function file(string $model, string $field = 'file'): FileUpload
    {
        return FileUpload::make($field)
            ->label(__('app.label.file'))
            ->disk('public')
            ->directory(fn (): string => "uploads/{$model}/".now()->format('Y/m'))
            ->visibility('public')
            ->acceptedFileTypes(self::DOCUMENT_TYPES)
            ->downloadable()
            ->maxSize(20480);
    }

    /**
     * @return array<string, string>
     */
    public static function localeOptions(): array
    {
        return collect(app_locales())
            ->mapWithKeys(fn (string $locale): array => [$locale => __("app.label.{$locale}")])
            ->all();
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
            ->afterStateHydrated(fn (Select $component, ?string $state) => $component->state(Social::iconName($state)));
    }

    /**
     * @param  class-string  $taxonomy
     */
    public static function category(string $taxonomy, string $field = 'category_id'): Select
    {
        return Select::make($field)
            ->label(__('app.label.category'))
            ->helperText(__('app.helper.entity_category'))
            ->options($taxonomy::options())
            ->searchable();
    }

    /**
     * The design ships four feature icons and the card layout is built around
     * them, so the field picks one rather than uploading a fifth.
     */
    public static function featureIcon(string $field = 'icon'): Select
    {
        return Select::make($field)
            ->label(__('app.label.icon'))
            ->helperText(__('app.helper.tariff_feature_icon'))
            ->options(collect(['phone', 'sms', 'globe', 'speed'])
                ->mapWithKeys(fn (string $icon): array => [$icon => __("app.feature_icon.{$icon}")])
                ->all())
            ->native(false);
    }

    public static function network(string $field = 'network'): Select
    {
        return Select::make($field)
            ->label(__('app.label.network'))
            ->helperText(__('app.helper.network'))
            ->options(Network::getOptions())
            ->default(Network::Both->value)
            ->required();
    }
}
