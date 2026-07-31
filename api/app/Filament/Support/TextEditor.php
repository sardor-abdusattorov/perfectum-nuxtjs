<?php

namespace App\Filament\Support;

use Filament\Forms\Components\RichEditor as FilamentRichEditor;

class TextEditor
{
    /**
     * Pre-configured RichEditor with file attachments stored in public disk.
     *
     * Saves to: storage/app/public/attachments/2025/03/
     * Public URL: /storage/attachments/2025/03/
     *
     * Usage:
     *   TextEditor::make('content')
     *   TextEditor::make('bio')->extraInputAttributes(['style' => 'min-height: 8rem;'])
     */
    public static function make(string $field): FilamentRichEditor
    {
        return FilamentRichEditor::make($field)
            ->fileAttachmentsDisk('public')
            ->fileAttachmentsDirectory(fn () => 'uploads/attachments/' . now()->format('Y/m'))
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
}
