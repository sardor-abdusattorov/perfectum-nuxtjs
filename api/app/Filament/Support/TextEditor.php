<?php

namespace App\Filament\Support;

use Filament\Forms\Components\RichEditor as FilamentRichEditor;

class TextEditor
{
    /**
     * Pre-configured RichEditor with attachments stored into
     * uploads/attachments/{Y}/{m} on the public disk.
     */
    public static function make(string $field): FilamentRichEditor
    {
        return FilamentRichEditor::make($field)
            ->fileAttachmentsDisk('public')
            ->fileAttachmentsDirectory(fn () => 'uploads/attachments/'.now()->format('Y/m'))
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
