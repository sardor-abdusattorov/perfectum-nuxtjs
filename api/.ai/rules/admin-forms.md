---
globs: app/Filament/**, app/Support/Slug.php
---

# Admin forms

## The slug is optional and generated server-side

`SlugInput` is not `->required()`. An empty slug is filled in
`mutateFormDataBeforeCreate` / `mutateFormDataBeforeSave` by the
`GeneratesSlug` trait, which every Create and Edit page of a slugged
resource uses. Marking the field required again silently disables the whole
mechanism — validation rejects the empty value before the mutation runs.

`Slug::make()` picks the first filled translation (English first, it reads
best in a URL), transliterates, truncates to 96 characters and appends
`-2`, `-3`, … until the slug is free. Pass a scope for a table whose unique
index is composite:

```php
protected function slugScope(array $data): array
{
    return ['type' => $data['type'] ?? null];
}
```

`fillSlug()` reads `title` first and falls back to `name`; no model has
both, so a resource never has to say which one it uses.

While the form is open, `SlugInput::preview()` mirrors the title into the
slug field on create. It calls `Slug::base()` so the preview matches what
gets stored — do not reach for `Str::slug()` here, it skips the length cap
and the empty-value fallback.

## Translated fields are required only in ru and uz

Inside `TranslatableTabs`, use `->required(Translated::required())` rather
than `->required()`. The plain call applies to every locale tab and blocks
publishing until all three are written, while everything that reads
translated content already falls back to a filled locale.

The list lives in `config('app.required_locales')` — change it there, not in
the forms.

## Build a field from `app/Filament/Support`, do not paste one

Three classes hold every repeated fragment — `Fields` for form inputs,
`Tables` for columns, filters and actions, `Translated` for the locale
rules. Add a method to one of them rather than a new file: the folder was
sixteen one-method classes and became unreadable.

Both editors are `RichEditor`; they differ in reach.
`Fields::multiline()` has no file uploads and no block buttons — inline
marks only — for headings and leads where the editor mostly supplies a
manual line break. `Fields::editor()` adds headings, lists, tables and
attachments, and belongs on `content`.

Everything either one produces is HTML, so the frontend prints it with
`v-html="rich(value)"`. `rich()` unwraps a lone `<p>` so the value can sit
inside an `<h2>` or a `<p>` without nesting a block element in one.

| Helper | Replaces |
| --- | --- |
| `Fields::slug()` / `::slugPreview()` | the slug field and its live preview |
| `Fields::sort()` | the `sort` number input |
| `Fields::status()` | the publish switch on a form or repeater item |
| `Fields::multiline()` | inline-only editor for headings and leads |
| `Fields::editor()` | the full editor, with attachments, for `content` |
| `Fields::image()` | the image upload with the crop editor |
| `Fields::icon()` | the social-network icon picker |
| `Fields::category($type)` | a category picker scoped to one `CategoryType` |
| `Tables::statusColumn()` | the publish `ToggleColumn` |
| `Tables::statusFilter()` | the published/unpublished `SelectFilter` |
| `Tables::categoryFilter($type)` | the category filter, scoped the same way |
| `Tables::actions()` / `::bulkActions()` | view/edit/delete and bulk-delete |
| `Translated::itemLabel($field)` | a repeater item label from a translated field |
| `Translated::required()` | per-locale requiredness inside `TranslatableTabs` |
| `SaveAction::make(self::class)` | the save button of a homepage tab |

`Tables::categoryFilter()` and `Fields::category()` share `Category::options()`, so a
filter always lists the same rows the form offers. A raw
`SelectFilter::make('category')->relationship('category', 'slug')` lists
*every* category in the table — news, faq, device — and shows raw slugs.
