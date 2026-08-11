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

Every repeated form and table fragment has a factory there, and each is the
one place to change that fragment for the whole panel:

| Helper | Replaces |
| --- | --- |
| `SlugInput::make()` / `::preview()` | the slug field and its live preview |
| `SortInput::make()` | the `sort` number input |
| `StatusToggle::make()` | the publish switch on a form or repeater item |
| `StatusColumn::make()` | the publish `ToggleColumn` on a table |
| `StatusFilter::make()` | the published/unpublished `SelectFilter` |
| `CategorySelect::make($type)` | a category picker scoped to one `CategoryType` |
| `CategoryFilter::make($type)` | the matching table filter, scoped the same way |
| `CrudActions::record()` / `::bulk()` | the view/edit/delete and bulk-delete arrays |
| `Translated::itemLabel($field)` | a repeater item label taken from a translated field |
| `Translated::required()` | per-locale requiredness inside `TranslatableTabs` |
| `TabSaveAction::make(self::class)` | the save button of a homepage tab |

`CategoryFilter` and `CategorySelect` share `Category::options()`, so a
filter always lists the same rows the form offers. A raw
`SelectFilter::make('category')->relationship('category', 'slug')` lists
*every* category in the table — news, faq, device — and shows raw slugs.
