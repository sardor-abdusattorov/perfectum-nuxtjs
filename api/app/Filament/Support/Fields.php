<?php

namespace App\Filament\Support;

use App\Enums\Network;
use App\Enums\SocialIcon;
use App\Models\Social;
use App\Support\Slug;
use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\Actions\AttachFilesAction;
use Filament\Forms\Components\RichEditor\EditorCommand;
use Filament\Forms\Components\RichEditor\RichEditorTool;
use Filament\Forms\Components\RichEditor\TextColor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class Fields
{
    /**
     * @var array<int, string>
     */
    public const DOCUMENT_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    /**
     * @var array<int, string>
     */
    public const IMAGE_TYPES = [
        'image/png',
        'image/jpeg',
        'image/gif',
        'image/webp',
        'image/svg+xml',
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
            ->live(onBlur: true)
            ->afterStateUpdated(fn (Set $set, ?string $state) => $set($field, Slug::fromInput($state)))
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

    /**
     * Гаснет и блокируется, как только запись показывают на сайте: два
     * включённых тумблера означали бы, что снятие с публикации не убирает
     * запись, а возвращает её к чтению по ссылке.
     */
    public static function byLink(string $field = 'by_link'): Toggle
    {
        return Toggle::make($field)
            ->label(__('app.label.by_link'))
            ->helperText(__('app.helper.by_link'))
            ->default(false)
            ->disabled(fn (Get $get): bool => (bool) $get('status'))
            ->dehydrated();
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
            ->fileAttachmentsAcceptedFileTypes(self::IMAGE_TYPES)
            ->registerActions([self::attachFilesWithImageEditor(), self::attachDocumentAction()])
            ->tools([
                RichEditorTool::make('attachDocument')
                    ->label(__('app.label.attach_document'))
                    ->action()
                    ->icon(Heroicon::OutlinedDocumentArrowDown),
            ])
            ->toolbarButtons([
                ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
                ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'paragraph'],
                ['alignStart', 'alignCenter', 'alignEnd', 'alignJustify'],
                ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                ['highlight', 'textColor', 'clearFormatting'],
                ['details', 'horizontalRule', 'lead', 'small', 'code'],
                ['table', 'attachFiles', 'attachDocument'],
                ['grid'],
                ['undo', 'redo'],
                ['fullscreen'],
            ]);
    }

    /**
     * The editor's own attachment button always inserts an image node, so a PDF
     * put through it renders as a broken picture. This one stores the file on
     * the public disk straight away and drops a plain link in its place —
     * a price list or a contract is something the visitor downloads, not looks
     * at inside the article.
     */
    private static function attachDocumentAction(): Action
    {
        return Action::make('attachDocument')
            ->label(__('app.label.attach_document'))
            ->modalWidth(Width::Large)
            ->schema([
                self::file('documents')
                    ->required(),

                TextInput::make('label')
                    ->label(__('app.label.link_text'))
                    ->helperText(__('app.helper.attach_document'))
                    ->maxLength(255),
            ])
            ->action(function (array $arguments, array $data, RichEditor $component): void {
                $path = $data['file'] ?? null;
                $url = stored_url(is_array($path) ? reset($path) : $path);

                if (blank($url)) {
                    return;
                }

                $component->runCommands(
                    [EditorCommand::make('insertContent', arguments: [[
                        'type' => 'text',
                        'text' => Str::of((string) ($data['label'] ?? ''))->trim()->value()
                            ?: basename((string) $url),
                        'marks' => [[
                            'type' => 'link',
                            'attrs' => ['href' => $url, 'target' => '_blank', 'rel' => 'noopener'],
                        ]],
                    ]])],
                    editorSelection: $arguments['editorSelection'] ?? null,
                );
            });
    }

    private static function attachFilesWithImageEditor(): Action
    {
        return AttachFilesAction::make()
            ->schema(fn (array $arguments, RichEditor $component): array => [
                FileUpload::make('file')
                    ->label(filled($arguments['src'] ?? null)
                        ? __('filament-forms::components.rich_editor.actions.attach_files.modal.form.file.label.existing')
                        : __('filament-forms::components.rich_editor.actions.attach_files.modal.form.file.label.new'))
                    ->acceptedFileTypes($component->getFileAttachmentsAcceptedFileTypes())
                    ->maxSize($component->getFileAttachmentsMaxSize())
                    ->storeFiles(false)
                    ->imageEditor()
                    ->required(blank($arguments['src'] ?? null))
                    ->hiddenLabel(blank($arguments['src'] ?? null)),

                TextInput::make('alt')
                    ->label(filled($arguments['src'] ?? null)
                        ? __('filament-forms::components.rich_editor.actions.attach_files.modal.form.alt.label.existing')
                        : __('filament-forms::components.rich_editor.actions.attach_files.modal.form.alt.label.new'))
                    ->maxLength(1000),
            ]);
    }

    private static function richEditor(string $field, string $size): RichEditor
    {
        return RichEditor::make($field)
            ->stateCast(new RichContentStateCast)
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

    public static function files(string $model, string $field = 'files'): FileUpload
    {
        return self::file($model, $field)
            ->label(__('app.label.documents'))
            ->multiple()
            ->reorderable();
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
